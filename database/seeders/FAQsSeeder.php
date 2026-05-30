<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FAQsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::query()->delete();

        $faqs = [
            [
                'question' => [
                    'en' => 'What is Rega?',
                    'ar' => 'ما هي ريكا؟',
                    'ckb' => 'ڕێگا چییە؟',
                ],
                'answer' => [
                    'en' => 'Rega is a public transit map for bus routes and stops. It helps riders find lines, view stops, and plan trips from a simple web browser.',
                    'ar' => 'ريكا خريطة نقل عام لمسارات الحافلات ومحطاتها. تساعد الركاب على العثور على الخطوط، عرض المحطات، وتخطيط الرحلات من متصفح ويب بسيط.',
                    'ckb' => 'ڕێگا نەخشەیەکی هاتوچۆی گشتییە بۆ ڕێگا و وێستگەکانی پاس. یارمەتی گەشتیاران دەدات هێڵەکان بدۆزنەوە، وێستگەکان ببینن، و گەشتەکانیان لە وێبگەڕێکی سادەدا پلاندانێن.',
                ],
            ],
            [
                'question' => [
                    'en' => 'How do I find a bus route?',
                    'ar' => 'كيف أجد مسار حافلة؟',
                    'ckb' => 'چۆن ڕێگای پاس بدۆزمەوە؟',
                ],
                'answer' => [
                    'en' => 'Open the transit map, search for a stop or bus line, or choose a start stop and destination stop. Rega then shows the matching route on the map.',
                    'ar' => 'افتح خريطة النقل، وابحث عن محطة أو خط حافلة، أو اختر محطة بداية ومحطة وجهة. بعد ذلك تعرض ريكا المسار المناسب على الخريطة.',
                    'ckb' => 'نەخشەی هاتوچۆ بکەرەوە و بەدوای وێستگە یان هێڵی پاسدا بگەڕێ، یان وێستگەی دەستپێک و وێستگەی مەبەست هەڵبژێرە. پاشان ڕێگا ڕێگای گونجاو لەسەر نەخشە پیشان دەدات.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Does Rega show live buses?',
                    'ar' => 'هل تعرض ريكا الحافلات بشكل مباشر؟',
                    'ckb' => 'ئایا ڕێگا پاسەکان بە ڕاستەوخۆ پیشان دەدات؟',
                ],
                'answer' => [
                    'en' => 'Rega is designed first for clear route and stop guidance. Live vehicle tracking can be added when supported by transit operators and the required devices.',
                    'ar' => 'تم تصميم ريكا أولاً لتقديم إرشاد واضح للمسارات والمحطات. يمكن إضافة تتبع الحافلات المباشر عندما تدعمه الجهات المشغلة والأجهزة المطلوبة.',
                    'ckb' => 'ڕێگا سەرەتا بۆ ڕێنمایی ڕوونی ڕێگا و وێستگە دیزاین کراوە. شوێنکەوتنی ڕاستەوخۆی پاس دەتوانرێت زیادبکرێت کاتێک لەلایەن بەڕێوەبەران و ئامێرە پێویستەکان پشتگیری بکرێت.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Can I use Rega in more than one language?',
                    'ar' => 'هل يمكنني استخدام ريكا بأكثر من لغة؟',
                    'ckb' => 'دەتوانم ڕێگا بە زیاتر لە یەک زمان بەکاربهێنم؟',
                ],
                'answer' => [
                    'en' => 'Yes. Rega supports Kurdish, Arabic, and English, including right-to-left layout support where it is needed.',
                    'ar' => 'نعم. تدعم ريكا الكردية والعربية والإنجليزية، مع دعم اتجاه الكتابة من اليمين إلى اليسار عند الحاجة.',
                    'ckb' => 'بەڵێ. ڕێگا پشتگیری کوردی، عەرەبی و ئینگلیزی دەکات، لەگەڵ پشتگیری ڕێکخستنی ڕاست بۆ چەپ کاتێک پێویست بێت.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Can I save favorite stops?',
                    'ar' => 'هل يمكنني حفظ المحطات المفضلة؟',
                    'ckb' => 'دەتوانم وێستگە دڵخوازەکان پاشەکەوت بکەم؟',
                ],
                'answer' => [
                    'en' => 'Yes. Sign in to save favorite stops and open them again from your favorites page.',
                    'ar' => 'نعم. سجّل الدخول لحفظ المحطات المفضلة وفتحها لاحقاً من صفحة المفضلة.',
                    'ckb' => 'بەڵێ. بچۆ ژوورەوە بۆ پاشەکەوتکردنی وێستگە دڵخوازەکان و کردنەوەیان لە پەڕەی دڵخوازەکان.',
                ],
            ],
            [
                'question' => [
                    'en' => 'What are nearby stops?',
                    'ar' => 'ما هي المحطات القريبة؟',
                    'ckb' => 'وێستگە نزیکەکان چین؟',
                ],
                'answer' => [
                    'en' => 'Nearby stops are stops close to the one you select. They help you compare easier pickup points or backup options for your trip.',
                    'ar' => 'المحطات القريبة هي المحطات الموجودة بالقرب من المحطة التي تختارها. تساعدك على مقارنة نقاط ركوب أسهل أو خيارات بديلة للرحلة.',
                    'ckb' => 'وێستگە نزیکەکان ئەو وێستگانەن کە لە وێستگەی هەڵبژێردراوت نزیکن. یارمەتیت دەدەن خاڵە ئاسانترەکانی سەرکەوتن یان هەڵبژاردەی جێگرەوە بۆ گەشتەکەت بەراورد بکەیت.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Can I send a route or stop issue?',
                    'ar' => 'هل يمكنني الإبلاغ عن مشكلة في مسار أو محطة؟',
                    'ckb' => 'دەتوانم کێشەی ڕێگا یان وێستگە بنێرم؟',
                ],
                'answer' => [
                    'en' => 'Yes. Use the contact page to send corrections, missing stop notes, or route feedback. Good feedback keeps the map useful for everyone.',
                    'ar' => 'نعم. استخدم صفحة التواصل لإرسال التصحيحات، ملاحظات المحطات الناقصة، أو ملاحظات المسارات. الملاحظات الجيدة تجعل الخريطة مفيدة للجميع.',
                    'ckb' => 'بەڵێ. پەڕەی پەیوەندی بەکاربهێنە بۆ ناردنی ڕاستکردنەوە، تێبینیی وێستگەی کەم، یان فیدباکی ڕێگا. فیدباکی باش نەخشەکە بۆ هەمووان سوودبەخشتر دەکات.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Do I need to install an app?',
                    'ar' => 'هل أحتاج إلى تثبيت تطبيق؟',
                    'ckb' => 'پێویستە ئەپ دابمەزرێنم؟',
                ],
                'answer' => [
                    'en' => 'No. Rega runs in a modern browser, so you can open the map from a phone, tablet, laptop, or desktop.',
                    'ar' => 'لا. تعمل ريكا في متصفح حديث، لذلك يمكنك فتح الخريطة من الهاتف أو الجهاز اللوحي أو الحاسوب المحمول أو المكتبي.',
                    'ckb' => 'نەخێر. ڕێگا لە وێبگەڕێکی نوێدا کاردەکات، بۆیە دەتوانیت نەخشەکە لە مۆبایل، تابلێت، لاپتۆپ یان کۆمپیوتەر بکەیتەوە.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Is Rega free to use?',
                    'ar' => 'هل استخدام ريكا مجاني؟',
                    'ckb' => 'ئایا بەکارهێنانی ڕێگا بەخۆڕاییە؟',
                ],
                'answer' => [
                    'en' => 'Yes. Rega is built as a public map experience. Account features such as favorites are optional.',
                    'ar' => 'نعم. تم بناء ريكا كتجربة خريطة عامة. ميزات الحساب مثل المفضلة اختيارية.',
                    'ckb' => 'بەڵێ. ڕێگا وەک ئەزموونێکی نەخشەی گشتی دروستکراوە. تایبەتمەندییەکانی هەژمار وەک دڵخوازەکان ئارەزوومەندانەن.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Why can I not find a route?',
                    'ar' => 'لماذا لا أستطيع العثور على مسار؟',
                    'ckb' => 'بۆچی ناتوانم ڕێگایەک بدۆزمەوە؟',
                ],
                'answer' => [
                    'en' => 'Try clearing filters, checking the spelling, or choosing a different start or destination stop. Some route options may not be available yet.',
                    'ar' => 'جرّب مسح الفلاتر، التحقق من الكتابة، أو اختيار محطة بداية أو وجهة مختلفة. قد لا تكون بعض خيارات المسارات متاحة بعد.',
                    'ckb' => 'هەوڵبدە پاڵێوەرەکان بسڕیتەوە، ڕێنووسەکە بپشکنیت، یان وێستگەی دەستپێک یان مەبەستی جیاواز هەڵبژێریت. هەندێک هەڵبژاردەی ڕێگا لەوانەیە هێشتا بەردەست نەبن.',
                ],
            ],
            [
                'question' => [
                    'en' => 'What do the map markers mean?',
                    'ar' => 'ماذا تعني علامات الخريطة؟',
                    'ckb' => 'نیشانەکانی نەخشە چی دەگەیەنن؟',
                ],
                'answer' => [
                    'en' => 'Markers represent bus stops. Select a marker to view stop details, connected lines, and nearby stops.',
                    'ar' => 'تمثل العلامات محطات الحافلات. اختر علامة لعرض تفاصيل المحطة، الخطوط المرتبطة، والمحطات القريبة.',
                    'ckb' => 'نیشانەکان نوێنەرایەتی وێستگەکانی پاس دەکەن. نیشانەیەک هەڵبژێرە بۆ بینینی وردەکاریی وێستگە، هێڵە پەیوەندیدارەکان، و وێستگە نزیکەکان.',
                ],
            ],
            [
                'question' => [
                    'en' => 'How does Rega keep the map easy to use?',
                    'ar' => 'كيف تجعل ريكا الخريطة سهلة الاستخدام؟',
                    'ckb' => 'ڕێگا چۆن نەخشەکە ئاسان بۆ بەکارهێنان دەهێڵێتەوە؟',
                ],
                'answer' => [
                    'en' => 'Rega focuses on clear route cards, simple filters, readable stop names, fast map interactions, and a clean design that works across screen sizes.',
                    'ar' => 'تركز ريكا على بطاقات مسارات واضحة، فلاتر بسيطة، أسماء محطات مقروءة، تفاعل سريع مع الخريطة، وتصميم نظيف يعمل على مختلف أحجام الشاشات.',
                    'ckb' => 'ڕێگا تەرکیز دەکاتە سەر کارتی ڕێگای ڕوون، پاڵێوەری سادە، ناوی وێستگەی خوێندراوە، کارلێکی خێرای نەخشە، و دیزاینی پاک کە لە قەبارەی جیاوازی شاشەدا کاردەکات.',
                ],
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
