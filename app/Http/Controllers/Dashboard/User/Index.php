<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Enums\StatusEnum;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Traits\WithNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

#[Title('User Management')]
class Index extends Component
{
    use WithFileUploads;
    use WithNotification;
    use WithPagination;

    public UserForm $form;

    #[Url()]
    public string $search = '';

    #[Url()]
    public string $status = 'all';

    #[Locked]
    public string $type = 'user';

    public function mount($type = null): void
    {
        $this->type = in_array($type, ['admin','user']) ? $type : 'user';
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $users = User::where('user_type', $this->type)
            ->when('all' !== $this->status, fn($query) => $query->where('status', $this->status))
            ->search($this->search)
            ->whereNot('id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('dashboard.user.index', ['users' => $users]);
    }

    public function updated($property, $value): void
    {
        if (in_array($property, ['search', 'status'])) {
            if ('status' === $property) {
                $this->{$property} = in_array($value, StatusEnum::toArray()) ? $value : 'all';
            }

            $this->resetPage();
        }
    }

    public function submit(): void
    {
        if ('user' === $this->type) {
            return;
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $data = Arr::except($this->form->toArray(), ['password_confirmation', 'edit', 'user_id']);
            if ($this->form->edit) {
                unset($data['password']);
            }

            $user = User::updateOrCreate([
                'id' => $this->form->edit ? $this->form->user_id : null,
            ], $data);
        } catch (Throwable $throwable) {
            DB::rollBack();
            $this->error(__('messages.something_went_wrong'));
        } finally {
            if (isset($throwable)) {
                return;
            }

            $this->success(
                __($user->wasRecentlyCreated ? 'messages.created_successfully'
                    : 'messages.updated_successfully', ['attr' => __('pages.users.single')]),
            );
            DB::commit();
            $this->done();
        }
    }

    #[On('editUser')]
    public function edit($id): void
    {
        if ('user' === $this->type) {
            return;
        }

        $user = User::findOrFail($id);
        $this->form->edit = true;
        $this->form->user_id = $user['id'];
        $this->form->name = $user['name'];
        $this->form->username = $user['username'];
        $this->form->phone = $user['phone'];
        $this->form->email = $user['email'];
        $this->form->status = $user['status']->value;
    }

    public function delete($id): void
    {
        if ('user' === $this->type) {
            return;
        }

        User::findOrFail($id)->delete();
        $this->success(__('messages.deleted_successfully', ['attr' => __('pages.users.single')]));
    }

    public function toggleStatus($id): void
    {
        $user = User::findOrFail($id);
        $user->update(['status' => StatusEnum::Active === $user->status ? StatusEnum::Inactive : StatusEnum::Active]);
        $this->success(__('messages.updated_successfully', ['attr' => __('pages.users.single')]));
    }


    public function done(): void
    {
        $this->dispatch('closing-modal');
        $this->form->reset();
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
