<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Clinic;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use Illuminate\Database\Seeder;

class MedicalDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─── CLINICS (6) ──────────────────────────────────────────────
        $clinics = collect([
            ['name' => 'Toshkent Tibbiyot Akademiyasi Klinikasi', 'address' => 'Toshkent sh., Olmazor tumani, Farobiy ko\'chasi 2', 'phone' => '+998 71 214-88-00', 'description' => 'O\'zbekistonning yetakchi ko\'p tarmoqli tibbiyot muassasasi. Barcha yo\'nalishlarda diagnostika va davolash xizmatlari.'],
            ['name' => 'Republican Ixtisoslashtirilgan Kardiologiya Markazi', 'address' => 'Toshkent sh., Mirzo Ulug\'bek tumani, Osiyo ko\'chasi 4', 'phone' => '+998 71 237-38-73', 'description' => 'Yurak-qon tomir kasalliklari bo\'yicha O\'zbekistondagi eng yirik markaz.'],
            ['name' => 'Samarqand Viloyat Ko\'p Tarmoqli Tibbiyot Markazi', 'address' => 'Samarqand sh., Registon ko\'chasi 18', 'phone' => '+998 66 233-10-10', 'description' => 'Samarqand viloyatining asosiy ko\'p tarmoqli shifoxonasi.'],
            ['name' => 'Farg\'ona Shahar Poliklinikasi', 'address' => 'Farg\'ona sh., Al-Xorazmiy ko\'chasi 56', 'phone' => '+998 73 244-22-33', 'description' => 'Farg\'ona shahridagi eng yirik ambulator poliklinika xizmatlari.'],
            ['name' => 'Toshkent Pediatriya Tibbiyot Instituti Klinikasi', 'address' => 'Toshkent sh., Chilonzor tumani, Bog\'ishamol ko\'chasi 223', 'phone' => '+998 71 262-30-47', 'description' => 'Bolalar kasalliklari bo\'yicha ixtisoslashgan tibbiyot markazi.'],
            ['name' => 'Buxoro Viloyat Shifoxonasi', 'address' => 'Buxoro sh., Navoiy ko\'chasi 44', 'phone' => '+998 65 221-55-60', 'description' => 'Buxoro viloyatining ko\'p tarmoqli markaziy shifoxonasi.'],
        ])->map(fn($data) => Clinic::firstOrCreate(['name' => $data['name']], array_merge($data, ['is_active' => true])));

        // ─── DOCTORS (35) ─────────────────────────────────────────────
        $doctorsData = [
            // Terapevtlar
            ['name' => 'Karimov Aziz Baxtiyorovich',    'specialization' => 'Terapevt',            'phone_number' => '+998901234567', 'clinic_idx' => 0],
            ['name' => 'Rahimova Nodira Toshpulatovna',  'specialization' => 'Terapevt',            'phone_number' => '+998901234568', 'clinic_idx' => 2],
            // LOR
            ['name' => 'Mirzayev Jahongir Abdullayevich','specialization' => 'LOR (Otorinolaringolog)', 'phone_number' => '+998931112233', 'clinic_idx' => 0],
            ['name' => 'Tursunova Dilnoza Karimovna',   'specialization' => 'LOR (Otorinolaringolog)', 'phone_number' => '+998931112234', 'clinic_idx' => 3],
            // Pulmonolog
            ['name' => 'Abdullayev Sherzod Ilhomovich',  'specialization' => 'Pulmonolog',          'phone_number' => '+998945556677', 'clinic_idx' => 0],
            ['name' => 'Qodirova Maftuna Erkinovna',     'specialization' => 'Pulmonolog',          'phone_number' => '+998945556678', 'clinic_idx' => 2],
            // Kardiolog
            ['name' => 'Hasanov Botir Nuriddinovich',    'specialization' => 'Kardiolog',           'phone_number' => '+998917778899', 'clinic_idx' => 1],
            ['name' => 'Yusupova Zulfiya Rashidovna',    'specialization' => 'Kardiolog',           'phone_number' => '+998917778890', 'clinic_idx' => 1],
            // Gastroenterolog
            ['name' => 'To\'rayev Ulug\'bek Anvarovich', 'specialization' => 'Gastroenterolog',     'phone_number' => '+998933334455', 'clinic_idx' => 0],
            ['name' => 'Sharipova Kamola Bahodirovna',   'specialization' => 'Gastroenterolog',     'phone_number' => '+998933334456', 'clinic_idx' => 5],
            // Nevrolog
            ['name' => 'Nazarov Firdavs Rustamovich',    'specialization' => 'Nevrolog',            'phone_number' => '+998909998877', 'clinic_idx' => 0],
            ['name' => 'Ergasheva Sabohat Olimovna',     'specialization' => 'Nevrolog',            'phone_number' => '+998909998878', 'clinic_idx' => 2],
            // Dermatolog
            ['name' => 'Ismoilov Sardor Aliyevich',      'specialization' => 'Dermatolog',          'phone_number' => '+998946667788', 'clinic_idx' => 0],
            ['name' => 'Raximova Mohira Zarifovna',      'specialization' => 'Dermatolog',          'phone_number' => '+998946667789', 'clinic_idx' => 3],
            // Pediatr
            ['name' => 'Xo\'jayeva Dilorom Qobilovna',  'specialization' => 'Pediatr',             'phone_number' => '+998901122334', 'clinic_idx' => 4],
            ['name' => 'Sobirov Jamshid Murodovich',     'specialization' => 'Pediatr',             'phone_number' => '+998901122335', 'clinic_idx' => 4],
            // Oftalmolog
            ['name' => 'Murodov Doniyor Baxromovich',    'specialization' => 'Oftalmolog',          'phone_number' => '+998935554433', 'clinic_idx' => 0],
            // Endokrinolog
            ['name' => 'Normatova Gulnora Abdug\'aniyevna','specialization' => 'Endokrinolog',      'phone_number' => '+998917776655', 'clinic_idx' => 0],
            ['name' => 'Saidov Akmal Toxirovich',        'specialization' => 'Endokrinolog',        'phone_number' => '+998917776656', 'clinic_idx' => 5],
            // Urolog
            ['name' => 'Alimov Ravshan Karimovich',      'specialization' => 'Urolog',              'phone_number' => '+998944443322', 'clinic_idx' => 0],
            // Ginekolog
            ['name' => 'Umarova Feruza Shuhratovna',     'specialization' => 'Ginekolog',           'phone_number' => '+998933332211', 'clinic_idx' => 0],
            ['name' => 'Toshmatova Hilola Abdujabborovna','specialization' => 'Ginekolog',           'phone_number' => '+998933332212', 'clinic_idx' => 2],
            // Ortoped
            ['name' => 'Raxmatullayev Odil Saidovich',   'specialization' => 'Ortoped-travmatolog', 'phone_number' => '+998905554321', 'clinic_idx' => 0],
            // Stomatolog
            ['name' => 'Qodirov Behruz Farxodovich',     'specialization' => 'Stomatolog',          'phone_number' => '+998911234500', 'clinic_idx' => 3],
            // Anesteziolog
            ['name' => 'Jo\'rayev Asilbek Shavkatovich',  'specialization' => 'Allergolog-immunolog','phone_number' => '+998949876543', 'clinic_idx' => 0],
            // Onkolog
            ['name' => 'Xudoyberdiyev Nodir Erkinovich', 'specialization' => 'Onkolog',             'phone_number' => '+998907654321', 'clinic_idx' => 0],
            // Nefolog
            ['name' => 'Asqarov Baxtiyor To\'lqinovich', 'specialization' => 'Nefrolog',            'phone_number' => '+998946543210', 'clinic_idx' => 1],
            // Psixiatr
            ['name' => 'Mamatov Laziz Sobirovich',        'specialization' => 'Psixiatr',            'phone_number' => '+998935432100', 'clinic_idx' => 0],
            // Infeksionist
            ['name' => 'Rajabov Otabek Saidmurodovich',  'specialization' => 'Infeksionist',        'phone_number' => '+998901230099', 'clinic_idx' => 5],
            // Revmatolog
            ['name' => 'Sultonova Nigora Hamidovna',     'specialization' => 'Revmatolog',           'phone_number' => '+998917890011', 'clinic_idx' => 0],
            // Ftiziatr
            ['name' => 'Yodgorov Mansur Abdullayevich',  'specialization' => 'Ftiziatr (Sil shifokori)', 'phone_number' => '+998949870022', 'clinic_idx' => 2],
            // Gematolog
            ['name' => 'Toshpo\'latova Barno Kalandarovna','specialization' => 'Gematolog',          'phone_number' => '+998907650033', 'clinic_idx' => 0],
            // Proktolog
            ['name' => 'Xolmatov Sanjar Abdurashidovich','specialization' => 'Proktolog',            'phone_number' => '+998931230044', 'clinic_idx' => 0],
            // Otorinolaringolog bolalar uchun
            ['name' => 'Nurmatova Shahlo Ikromovna',     'specialization' => 'Bolalar LOR shifokori','phone_number' => '+998901230055', 'clinic_idx' => 4],
            // Bolalar nevrolg
            ['name' => 'Eshmatov Sardorbek Qo\'chqorovich','specialization' => 'Bolalar nevrologi', 'phone_number' => '+998901230066', 'clinic_idx' => 4],
        ];

        $doctors = collect($doctorsData)->map(function ($data) use ($clinics) {
            $clinicId = $clinics[$data['clinic_idx']]->id;
            unset($data['clinic_idx']);
            return Doctor::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['clinic_id' => $clinicId, 'is_active' => true])
            );
        });

        // Helper to find doctor by specialization keyword
        $findDoctors = function (string ...$keywords) use ($doctors) {
            return $doctors->filter(function ($d) use ($keywords) {
                foreach ($keywords as $kw) {
                    if (stripos($d->specialization, $kw) !== false) return true;
                }
                return false;
            })->values();
        };

        // ─── DISEASES (40) ────────────────────────────────────────────
        $diseasesData = [
            // Nafas yo'llari (0-150 yosh)
            ['name' => 'Gripp (Influenza)',       'category' => 'Nafas yo\'llari',    'description' => 'Gripp viruslari keltirib chiqaradigan o\'tkir yuqumli kasallik. Isitma, bosh og\'rig\'i, mushak og\'rig\'i va quruq yo\'tal bilan namoyon bo\'ladi.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Bronxit',                 'category' => 'Nafas yo\'llari',    'description' => 'Bronxlarning yallig\'lanishi. Yo\'tal, balg\'am ajralishi, nafas qisilishi kuzatiladi.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Pnevmoniya (O\'pka yallig\'lanishi)', 'category' => 'Nafas yo\'llari', 'description' => 'O\'pka to\'qimasining yallig\'lanishi. Yuqori isitma, yo\'tal, ko\'krak og\'rig\'i va nafas qisilishi.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Bronxial astma',          'category' => 'Nafas yo\'llari',    'description' => 'Nafas yo\'llarining surunkali yallig\'lanishi. Nafas qisilishi, xirillash va yo\'tal xurujlari bilan namoyon bo\'ladi.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Sinusit',                 'category' => 'Nafas yo\'llari',    'description' => 'Burun bo\'shliqlarining yallig\'lanishi. Burun bitishi, bosh og\'rig\'i, yuz sohasida og\'riq.', 'min_age' => 3, 'max_age' => 150],
            // Tomoq, quloq, burun
            ['name' => 'Angina (Tonzillit)',      'category' => 'LOR kasalliklari',   'description' => 'Bodomsimon bezlarning o\'tkir yallig\'lanishi. Tomoq og\'rig\'i, yutish qiyinligi, isitma.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Otit (Quloq yallig\'lanishi)', 'category' => 'LOR kasalliklari', 'description' => 'Quloqning yallig\'lanishi. Quloq og\'rig\'i, eshitish pasayishi, isitma.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Faringit',                'category' => 'LOR kasalliklari',   'description' => 'Halqum shilliq qavatining yallig\'lanishi. Tomoq og\'rig\'i, quruqlik, tirnoqlash hissi.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Laringit',                'category' => 'LOR kasalliklari',   'description' => 'Hiqildoqning yallig\'lanishi. Ovoz o\'zgarishi, bo\'g\'iq yo\'tal, nafas qiyinligi.', 'min_age' => 0, 'max_age' => 150],
            // Yurak-qon tomir
            ['name' => 'Gipertoniya (Qon bosimi)', 'category' => 'Yurak-qon tomir',  'description' => 'Arterial qon bosimining surunkali oshishi. Bosh og\'rig\'i, bosh aylanishi, yurak urishi tezlashishi.', 'min_age' => 18, 'max_age' => 150],
            ['name' => 'Stenokardiya',            'category' => 'Yurak-qon tomir',    'description' => 'Yurak mushaklariga qon yetishmasligidan ko\'krak sohasida og\'riq. Jismoniy zo\'riqishda kuchayadi.', 'min_age' => 30, 'max_age' => 150],
            ['name' => 'Aritmiya',                'category' => 'Yurak-qon tomir',    'description' => 'Yurak ritmining buzilishi. Yurak urishi tezlashishi, sekinlashishi yoki tartibsizligi.', 'min_age' => 14, 'max_age' => 150],
            // Oshqozon-ichak
            ['name' => 'Gastrit',                 'category' => 'Oshqozon-ichak',     'description' => 'Oshqozon shilliq qavatining yallig\'lanishi. Qorin og\'rig\'i, ko\'ngil aynish, ovqat hazm qilish buzilishi.', 'min_age' => 5, 'max_age' => 150],
            ['name' => 'Oshqozon yarasi',         'category' => 'Oshqozon-ichak',     'description' => 'Oshqozon yoki 12 barmoqli ichak shilliq qavatida yara. Oshqozon sohasida og\'riq, ko\'ngil aynish.', 'min_age' => 14, 'max_age' => 150],
            ['name' => 'Xoletsistit (O\'t pufagi yallig\'lanishi)', 'category' => 'Oshqozon-ichak', 'description' => 'O\'t pufagining yallig\'lanishi. O\'ng qovurg\'a ostida og\'riq, ko\'ngil aynish, achchiq ta\'m.', 'min_age' => 18, 'max_age' => 150],
            ['name' => 'Ich ketishi (Diareya)',   'category' => 'Oshqozon-ichak',     'description' => 'Tez-tez suyuq najas. Zaharlanish, infeksiya yoki boshqa sabablarga ko\'ra kelib chiqadi.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Ich qotishi (Qabziyat)',  'category' => 'Oshqozon-ichak',     'description' => 'Defekatsiyaning qiyinlashishi yoki kamayishi. Qorin dam bo\'lishi, og\'riq.', 'min_age' => 0, 'max_age' => 150],
            // Nevrologiya
            ['name' => 'Migren',                  'category' => 'Nevrologiya',        'description' => 'Kuchli, ko\'pincha bir tomonlama bosh og\'rig\'i. Ko\'ngil aynish, yorug\'lik va tovushga sezgirlik bilan.', 'min_age' => 10, 'max_age' => 150],
            ['name' => 'Nevralgiya',              'category' => 'Nevrologiya',        'description' => 'Nerv bo\'ylab tarqaladigan o\'tkir og\'riq. Sanchish, kuydirish, uvishish hissi.', 'min_age' => 14, 'max_age' => 150],
            ['name' => 'Osteoxondroz',            'category' => 'Nevrologiya',        'description' => 'Umurtqa pog\'onasi bo\'g\'im va diskalarining yemirilishi. Bo\'yin, bel og\'rig\'i, harakat cheklanishi.', 'min_age' => 18, 'max_age' => 150],
            ['name' => 'Uyqusizlik (Insomnia)',   'category' => 'Nevrologiya',        'description' => 'Uyquga ketish yoki uyquni saqlab turishda qiyinchilik. Charchoq, asabiylashish.', 'min_age' => 14, 'max_age' => 150],
            // Dermatologiya
            ['name' => 'Ekzema',                  'category' => 'Dermatologiya',      'description' => 'Terining surunkali yallig\'lanishi. Qichishish, qizarish, po\'rsildoqlar paydo bo\'lishi.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Dermatit',                'category' => 'Dermatologiya',      'description' => 'Terining yallig\'lanishi. Allergik yoki kontakt tabiatga ega. Qichishish, toshma, shish.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Zamburug\'li infeksiya',  'category' => 'Dermatologiya',      'description' => 'Terida, tirnoqlarda yoki sochda zamburug\' infeksiyasi. Qichishish, po\'stloq, rang o\'zgarishi.', 'min_age' => 0, 'max_age' => 150],
            // Endokrinologiya
            ['name' => 'Qandli diabet (2-tip)',   'category' => 'Endokrinologiya',    'description' => 'Qondagi qand miqdorining surunkali oshishi. Chanqash, tez-tez siydik qilish, charchoq.', 'min_age' => 18, 'max_age' => 150],
            ['name' => 'Qalqonsimon bez kasalligi','category' => 'Endokrinologiya',   'description' => 'Qalqonsimon bezning funksiya buzilishi. Vazn o\'zgarishi, charchoq, kayfiyat o\'zgarishi.', 'min_age' => 14, 'max_age' => 150],
            // Urologiya
            ['name' => 'Sistit (Qovuq yallig\'lanishi)', 'category' => 'Urologiya',  'description' => 'Siydik pufagining yallig\'lanishi. Tez-tez va og\'riqli siydik qilish, qorin pastki qismida og\'riq.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Buyrak toshi',            'category' => 'Urologiya',          'description' => 'Buyraklarda tosh hosil bo\'lishi. Bel sohasida kuchli og\'riq (renal kolik), qonli siydik.', 'min_age' => 18, 'max_age' => 150],
            // Ginekologiya
            ['name' => 'Mastit',                  'category' => 'Ginekologiya',       'description' => 'Sut bezining yallig\'lanishi. Ko\'krak og\'rig\'i, shishi, qizarishi va isitma.', 'min_age' => 14, 'max_age' => 60],
            // Ortopediya
            ['name' => 'Artrit (Bo\'g\'im yallig\'lanishi)', 'category' => 'Ortopediya', 'description' => 'Bo\'g\'imlarning yallig\'lanishi. Og\'riq, shish, qizarish va harakat cheklanishi.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Osteoporoz',              'category' => 'Ortopediya',         'description' => 'Suyak zichligining pasayishi. Suyaklar mo\'rtlashadi, sinish xavfi oshadi.', 'min_age' => 40, 'max_age' => 150],
            // Stomatologiya
            ['name' => 'Kariyes',                 'category' => 'Stomatologiya',      'description' => 'Tish yemirilishi. Tish og\'rig\'i, sovuq-issiqqa sezgirlik, tishda teshik.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Gingivit (Milk yallig\'lanishi)', 'category' => 'Stomatologiya', 'description' => 'Milklarning yallig\'lanishi. Milklar qon ketishi, shishishi, qizarishi.', 'min_age' => 5, 'max_age' => 150],
            // Allergologiya
            ['name' => 'Allergik rinit',          'category' => 'Allergologiya',      'description' => 'Burun shilliq qavatining allergik yallig\'lanishi. Hapqirish, burun oqishi, qichishish.', 'min_age' => 1, 'max_age' => 150],
            ['name' => 'Krapivnitsa',             'category' => 'Allergologiya',      'description' => 'Teridagi allergik reaktsiya. Qichishuvchi qizil dog\'lar, shish paydo bo\'lishi.', 'min_age' => 0, 'max_age' => 150],
            // Infeksion
            ['name' => 'COVID-19',                'category' => 'Infeksion kasalliklar', 'description' => 'Koronavirus infeksiyasi. Isitma, yo\'tal, hid va ta\'m sezishning yo\'qolishi, charchoq.', 'min_age' => 0, 'max_age' => 150],
            ['name' => 'Gepatit A',               'category' => 'Infeksion kasalliklar', 'description' => 'Jigar virusli yallig\'lanishi (A tipi). Sariqlik, holsizlik, ishtaha yo\'qolishi, qorin og\'rig\'i.', 'min_age' => 0, 'max_age' => 150],
            // Bolalar kasalliklari
            ['name' => 'Qizamiq',                 'category' => 'Bolalar infeksiyalari', 'description' => 'Virusli yuqumli kasallik. Isitma, tana bo\'ylab toshma, yo\'tal, ko\'z yallig\'lanishi.', 'min_age' => 0, 'max_age' => 18],
            ['name' => 'Suvchechak (Vetryanka)',  'category' => 'Bolalar infeksiyalari', 'description' => 'Virusli kasallik. Isitma va tanada suyuqlik to\'la pufakchalar toshma paydo bo\'lishi.', 'min_age' => 0, 'max_age' => 18],
            // Psixiatriya
            ['name' => 'Depressiya',              'category' => 'Psixiatriya',        'description' => 'Ruhiy tushkunlik holati. Surunkali g\'amginlik, qiziqishning yo\'qolishi, charchoq, uyqusizlik, ishtaha o\'zgarishi.', 'min_age' => 14, 'max_age' => 150],
        ];

        $diseaseModels = collect($diseasesData)->map(function ($data) {
            return Disease::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );
        });

        // ─── RECOMMENDATIONS + DOCTOR LINKS (40+) ────────────────────
        $recommendations = [
            ['disease' => 'Gripp (Influenza)', 'doctors' => ['Terapevt'], 'priority' => 10,
             'text' => "Gripp davolash bo'yicha tavsiyalar:\n• Dam olish va ko'p suyuqlik ichish (iliq choy, suv, kompot)\n• Harorat 38.5°C dan oshsa paratsetamol yoki ibuprofen (shifokor ko'rsatmasi bilan)\n• Burunni sho'r suv bilan yuvish\n• Xonani shamollatib turish\n• 3-5 kun ichida yaxshilanmasa yoki ahvol yomonlashsa shifokorga murojaat qiling\n• Antibiotik FAQAT shifokor buyurganda qo'llaning"],

            ['disease' => 'Bronxit', 'doctors' => ['Pulmonolog'], 'priority' => 10,
             'text' => "Bronxit davolash tavsiyelari:\n• Ko'p iliq suyuqlik ichish — balg'amni suyultiradi\n• Xona havasini namlab turish\n• Dam olish, jismoniy zo'riqishdan saqlaning\n• Quruq yo'talda — shifokor tavsiyasi bilan yo'talni bosuvchi dorilar\n• Balg'amli yo'talda — balg'am ko'chiruvchi vositalar (mukolitiklar)\n• 7-10 kun ichida yaxshilanmasa, qon tushurilsa — ZUDLIK bilan shifokorga"],

            ['disease' => 'Pnevmoniya (O\'pka yallig\'lanishi)', 'doctors' => ['Pulmonolog'], 'priority' => 15,
             'text' => "⚠️ Pnevmoniya jiddiy kasallik — shifokor nazorati SHART!\n• Antibiotik davolash faqat shifokor buyuradi\n• To'liq dam olish, karavotda yotish tavsiya etiladi\n• Ko'p suyuqlik ichish\n• Haroratni paratsetamol bilan tushirish\n• Nafas mashqlari (nafas olish gimnastikasi)\n• Agar nafas qisilishi kuchaysa, 103 ga qo'ng'iroq qiling"],

            ['disease' => 'Bronxial astma', 'doctors' => ['Pulmonolog', 'Allergolog'], 'priority' => 12,
             'text' => "Bronxial astma bilan ishlash:\n• Shifokor buyurgan ingalyator (salbutamol) doimo qo'l ostida bo'lsin\n• Allergenlarga (chang, gul changi, hayvon juni) yaqinlashmang\n• Chekishdan va tutundan uzoq bo'ling\n• Muntazam shifokor nazoratida bo'ling\n• Xuruj paytida: tinchlaning, ingalyatorni ishlating, 15 daqiqada yaxshilanmasa 103 ga qo'ng'iroq qiling"],

            ['disease' => 'Sinusit', 'doctors' => ['LOR'], 'priority' => 8,
             'text' => "Sinusit davolash:\n• Burunni sho'r suv bilan kuniga 3-4 marta yuvish\n• Burun tomchilar (oksimetazolin) — 5 kundan oshmasin\n• Ko'p iliq suyuqlik ichish\n• Yuz sohasiga issiq kompres qo'yish\n• 10 kundan ortiq davom etsa — antibiotik kerak bo'lishi mumkin (shifokor buyuradi)"],

            ['disease' => 'Angina (Tonzillit)', 'doctors' => ['LOR'], 'priority' => 12,
             'text' => "Angina davolash:\n• Shifokor ko'rigi va antibiotik tayinlash SHART (angina bakterial!)\n• Tomoqni iliq sho'r suv bilan chayqash (kuniga 4-5 marta)\n• Yumshoq, iliq ovqat iste'mol qilish\n• Ko'p suyuqlik ichish\n• Dam olish, karavotda yotish 3-5 kun\n• Antibiotikni to'liq kurs Bo'yicha ichish (o'rtada tashlamang!)"],

            ['disease' => 'Otit (Quloq yallig\'lanishi)', 'doctors' => ['LOR', 'Bolalar LOR'], 'priority' => 10,
             'text' => "Otit davolash:\n• Og'riqni kamaytirish — paratsetamol yoki ibuprofen\n• Quloqqa issiq kompres (lekin yiring bo'lsa MUMKIN EMAS)\n• Burun tomchilar — burundan nafas olishni yaxshilash uchun\n• Quloq tomchilar — FAQAT shifokor buyurganda\n• Yiringli otitda antibiotik shart — shifokor tayinlaydi\n• O'z-o'zidan davolanmang, ayniqsa bolalarda!"],

            ['disease' => 'Faringit', 'doctors' => ['LOR'], 'priority' => 7,
             'text' => "Faringit davolash:\n• Tomoqni iliq tuzli suv bilan chayqash\n• Iliq suyuqlik ichish (asal bilan choy)\n• Tomoq uchun antiseptik pastilkalar\n• Quruq havadan saqlaning — xonani namlang\n• 5-7 kunda o'tishi kerak, aks holda shifokorga boring"],

            ['disease' => 'Laringit', 'doctors' => ['LOR', 'Bolalar LOR'], 'priority' => 9,
             'text' => "Laringit davolash:\n• Ovoz paychalariga dam bering — iloji boricha gapirmang\n• Iliq (issiq EMAS) suyuqlik ichish\n• Bug' inhalatsiyasi (nebulayzer bilan yaxshiroq)\n• Chekish va tutundan uzoq bo'ling\n• Bolalarda laringit — XAVFLI bo'lishi mumkin (stenoz). Nafas qiyinlashsa — 103!"],

            ['disease' => 'Gipertoniya (Qon bosimi)', 'doctors' => ['Kardiolog'], 'priority' => 14,
             'text' => "Gipertoniya nazorati:\n• Qon bosimini muntazam o'lchab turing\n• Shifokor buyurgan dorilarni HAR KUNI, to'xtatmasdan iching\n• Tuzni kamaytiring (kuniga 5 gramdan kam)\n• Vazn normada bo'lsin — ortiqcha vaznni kamaytiring\n• Jismoniy faollik — kuniga 30 daqiqa yurish\n• Stress kamaytirilsin\n• Chekish va spirtli ichimliklarni tashang\n• 180/120 dan oshsa — 103 ga DARHOL qo'ng'iroq qiling!"],

            ['disease' => 'Stenokardiya', 'doctors' => ['Kardiolog'], 'priority' => 15,
             'text' => "⚠️ Stenokardiya — yurak kasalligi, shifokor nazorati SHART!\n• Nitroglitserini doimo qo'l ostida saqlang\n• Ko'krak og'rig'ida: o'tirib oling, nitroglitserin tiling, 5 daqiqa kuting\n• 15 daqiqada o'tmasa — 103 ga DARHOL qo'ng'iroq qiling!\n• Shifokor buyurgan dorilarni muntazam iching\n• Jismoniy zo'riqishdan ehtiyot bo'ling\n• Yog'li ovqatlarni kamaytiring, chekishni tashang"],

            ['disease' => 'Aritmiya', 'doctors' => ['Kardiolog'], 'priority' => 12,
             'text' => "Aritmiya nazorati:\n• EKG tekshiruvidan o'ting\n• Shifokor buyurgan dorilarni muntazam qabul qiling\n• Kofe va energetik ichimliklarni cheklang\n• Stress va haddan tashqari zo'riqishdan saqlaning\n• Yurak urishi 150+ yoki 40 dan past bo'lsa — 103 ga qo'ng'iroq qiling"],

            ['disease' => 'Gastrit', 'doctors' => ['Gastroenterolog'], 'priority' => 9,
             'text' => "Gastrit davolash:\n• Parhez — achchiq, nordon, yog'li, qovurilgan ovqatlardan voz keching\n• Kichik porsiyalarda, kuniga 5-6 marta ovqatlaning\n• Antatsidlar (oshqozon kislotasini kamaytiradigan dorilar) — shifokor buyurtmasi bilan\n• Chekish va spirtli ichimliklarni to'xtatish\n• FGDS tekshiruvidan o'ting (oshqozon zond tekshiruvi)"],

            ['disease' => 'Oshqozon yarasi', 'doctors' => ['Gastroenterolog'], 'priority' => 13,
             'text' => "⚠️ Oshqozon yarasi — shifokor davolashi SHART!\n• Qattiq parhez — achchiq, nordon, yog'li, qovurilgan taqiqlangan\n• Shifokor buyurgan dorilarni to'liq kurs bo'yicha iching\n• Helicobacter pylori testi topshiring\n• Stress kamaytiring\n• Qorin og'rig'i kuchaysa yoki qora rangli najas ko'rilsa — ZUDLIK bilan shifokorga!"],

            ['disease' => 'Xoletsistit (O\'t pufagi yallig\'lanishi)', 'doctors' => ['Gastroenterolog'], 'priority' => 10,
             'text' => "Xoletsistit davolash:\n• Yog'li va qovurilgan ovqatlardan to'liq voz keching\n• Kichik porsiyalarda ovqatlaning\n• UZI tekshiruvidan o'ting\n• Shifokor buyurgan dorilarni qabul qiling\n• O'ng qovurg'a ostida kuchli og'riq + isitma bo'lsa — ZUDLIK bilan shifoxonaga"],

            ['disease' => 'Ich ketishi (Diareya)', 'doctors' => ['Terapevt', 'Infeksionist'], 'priority' => 8,
             'text' => "Ich ketishi davolash:\n• Eng muhimi — suvdan qochmaslik! Regidron yoki tuzli suv ichish\n• BRAT parhezi: banan, guruch, olma, tost non\n• Enterosorbentlar (smekta, enterosgel)\n• Probiotiklar — ichak mikroflorasini tiklash\n• 3 kundan oshsa, qon aralash bo'lsa — DARHOL shifokorga\n• Bolalarda ich ketishi xavfli — tez suvsizlanadi!"],

            ['disease' => 'Ich qotishi (Qabziyat)', 'doctors' => ['Gastroenterolog'], 'priority' => 6,
             'text' => "Qabziyat davolash:\n• Kuniga 2 litr suv ichish\n• Tolali ovqatlar iste'mol qiling (sabzavotlar, mevalar, kepak)\n• Jismoniy harakatni oshiring — yurish, mashq qilish\n• Muntazam vaqtda hojatxonaga o'tiring\n• Surgilar (laksativlar) faqat shifokor tavsiyasi bilan"],

            ['disease' => 'Migren', 'doctors' => ['Nevrolog'], 'priority' => 10,
             'text' => "Migren bilan kurash:\n• Xuruj boshlanishida tinchlaning, qorong'i, jimjit xonaga o'ting\n• Og'riq qoldiruvchi (ibuprofen, paratsetamol) erta qabul qiling\n• Tez-tez takrorlanuvchi migrenda shifokor profilaktik dori buyuradi\n• Triggerlarni aniqlang: stress, uyqusizlik, ba'zi ovqatlar, havo o'zgarishi\n• Kundalik yuriting — xurujlar sababini topishga yordam beradi"],

            ['disease' => 'Nevralgiya', 'doctors' => ['Nevrolog'], 'priority' => 8,
             'text' => "Nevralgiya davolash:\n• Og'riq qoldiruvchi va yallig'lanishga qarshi dorilar\n• Fizioterapiya — issiqlik, elektroterapiya\n• Massaj (faqat mutaxassis tomonidan)\n• B guruhi vitaminlari\n• Sovuq va shamoldan himoyalaning"],

            ['disease' => 'Osteoxondroz', 'doctors' => ['Nevrolog', 'Ortoped'], 'priority' => 9,
             'text' => "Osteoxondroz davolash:\n• Maxsus mashqlar (LFK) — shifokor ko'rsatmasi bilan\n• To'g'ri yotish — ortopedik matras va yostiq\n• Massaj va fizioterapiya\n• Og'riq kuchli bo'lganda — og'riq qoldiruvchilar\n• Og'ir yuk ko'tarmaslik\n• Uzoq o'tirmaslik — har soatda turib harakatlaning"],

            ['disease' => 'Uyqusizlik (Insomnia)', 'doctors' => ['Nevrolog', 'Psixiatr'], 'priority' => 7,
             'text' => "Uyqusizlik bilan kurash:\n• Har kuni bir xil vaqtda yotish va turish\n• Uxlashdan 2 soat oldin telefon va kompyuterni qo'ying\n• Kechqurun kofe, choy va energetik ichmang\n• Jismoniy mashq qiling, lekin uxlashdan 3 soat oldin emas\n• Yotoq xonasini salqin, qorong'i va jim saqlang\n• Surunkali bo'lsa — shifokorga murojaat qiling"],

            ['disease' => 'Ekzema', 'doctors' => ['Dermatolog'], 'priority' => 8,
             'text' => "Ekzema davolash:\n• Terini muntazam namlang (emolyentlar)\n• Allergik mahsulotlardan uzoq bo'ling\n• Qichishgan joyni qashlamang — yara bo'ladi\n• Shifokor buyurgan kremlar va malhamlarni ishlating\n• Sintetik kiyimlardan voz keching — paxta kiyimlar kiying"],

            ['disease' => 'Dermatit', 'doctors' => ['Dermatolog', 'Allergolog'], 'priority' => 7,
             'text' => "Dermatit davolash:\n• Allergenni aniqlang va undan uzoqlashing\n• Antihistamin dorilar (loratadin, tsetirizin)\n• Mahalliy gormon kremlari — faqat shifokor buyurtmasi bilan\n• Terini namlang\n• Kuchli sovun va kimyoviy moddalardan saqlaning"],

            ['disease' => 'Zamburug\'li infeksiya', 'doctors' => ['Dermatolog'], 'priority' => 7,
             'text' => "Zamburug'li infeksiya davolash:\n• Zararlangan joyni quruq va toza saqlang\n• Antifungal (zamburug'ga qarshi) krem yoki malham — shifokor buyuradi\n• Boshqalarning shaxsiy buyumlarini ishlatmang\n• Poyabzalni dezinfeksiya qiling\n• Davolashni to'liq tugating — yarim yo'lda tashlasangiz qaytadan chiqadi"],

            ['disease' => 'Qandli diabet (2-tip)', 'doctors' => ['Endokrinolog'], 'priority' => 14,
             'text' => "⚠️ Qandli diabet — umrbod nazorat talab etadi!\n• Qondagi qandni muntazam o'lchang\n• Shifokor buyurgan dorilarni DOIMO iching\n• Parhez: shakar, oq non, shirin ichimliklarni qattiq cheklang\n• Jismoniy faollik kuniga 30+ daqiqa\n• Vaznni normalda saqlang\n• Oyoqlarni har kuni tekshiring (yaralar, o'zgarishlar)\n• Yiliga 2 marta shifokor nazoratiga keling"],

            ['disease' => 'Qalqonsimon bez kasalligi', 'doctors' => ['Endokrinolog'], 'priority' => 10,
             'text' => "Qalqonsimon bez davolash:\n• TTG, T3, T4 tahlillari topshiring\n• UZI tekshiruvidan o'ting\n• Shifokor buyurgan gormon dorilarini muntazam iching\n• Yodga boy ovqatlar iste'mol qiling (dengiz mahsulotlari)\n• Dozani o'zingiz o'zgartirmang!"],

            ['disease' => 'Sistit (Qovuq yallig\'lanishi)', 'doctors' => ['Urolog'], 'priority' => 9,
             'text' => "Sistit davolash:\n• Ko'p suyuqlik ichish (kuniga 2-3 litr)\n• Antibiotik — FAQAT shifokor buyurtmasi bilan\n• Qorin pastiga iliq kompres\n• Achchiq va tuzli ovqatlardan voz keching\n• Sovuq joyda o'tirmaslik\n• 3 kunda yaxshilanmasa — takroran shifokorga murojaat"],

            ['disease' => 'Buyrak toshi', 'doctors' => ['Urolog', 'Nefrolog'], 'priority' => 12,
             'text' => "⚠️ Buyrak toshi — tekshiruv SHART!\n• UZI va tahlil topshiring\n• Ko'p suv iching\n• Og'riq kuchli bo'lsa — og'riq qoldiruvchi va tez yordam\n• Kichik toshlar tabbiiy chiqishi mumkin — shifokor kuzatadi\n• Katta toshlar — operatsiya yoki urish kerak bo'lishi mumkin\n• Bel og'rig'i + isitma + qonli siydik = DARHOL 103!"],

            ['disease' => 'Mastit', 'doctors' => ['Ginekolog'], 'priority' => 10,
             'text' => "Mastit davolash:\n• Emizishni TO'XTATMASLIK (yaxshiroq bo'lishi uchun tez-tez emizish)\n• Issiq kompres — emizishdan oldin\n• Sovuq kompres — emizishdan keyin (shishni kamaytiradi)\n• Antibiotik — shifokor buyurtmasi bilan\n• Yiringli mastit — operatsiya kerak bo'lishi mumkin, kechiktirmang!"],

            ['disease' => 'Artrit (Bo\'g\'im yallig\'lanishi)', 'doctors' => ['Ortoped', 'Revmatolog'], 'priority' => 9,
             'text' => "Artrit davolash:\n• Yallig'lanishga qarshi dorilar (NSAID) — shifokor buyuradi\n• Fizioterapiya va maxsus mashqlar\n• Ortiq vaznni kamaytiring — bo'g'imlarga yuk tushadi\n• Sovuqdan himoyalaning\n• Surunkali artritda — revmatolog nazoratida bo'ling"],

            ['disease' => 'Osteoporoz', 'doctors' => ['Ortoped', 'Endokrinolog'], 'priority' => 8,
             'text' => "Osteoporoz oldini olish va davolash:\n• Kalsiy va D vitamini qabul qiling\n• Sut mahsulotlari ko'proq iste'mol qiling\n• Jismoniy mashqlar — yurish, suzish\n• Yiqilishdan ehtiyot bo'ling\n• Densitometriya tekshiruvi (suyak zichligi) — yiliga 1 marta"],

            ['disease' => 'Kariyes', 'doctors' => ['Stomatolog'], 'priority' => 7,
             'text' => "Kariyes oldini olish va davolash:\n• Kuniga 2 marta tish yuvish (fluoridli pasta bilan)\n• Tish ipi ishlatish\n• Shirin ovqat va ichimliklarni cheklash\n• Yiliga 2 marta stomatologga tekshiruvga boring\n• Tish og'rig'i bo'lsa — kechiktirmang, zudlik bilan stomatologga!"],

            ['disease' => 'Gingivit (Milk yallig\'lanishi)', 'doctors' => ['Stomatolog'], 'priority' => 6,
             'text' => "Gingivit davolash:\n• Tish toshini tozalash (stomatologda)\n• Yumshoq cho'tkasi bilan to'g'ri tish yuvish\n• Antiseptik chayqash vositalari (xlorheksidin)\n• Davolanmasa parodontitga o'tadi — tish yoqolishi mumkin!"],

            ['disease' => 'Allergik rinit', 'doctors' => ['Allergolog', 'LOR'], 'priority' => 8,
             'text' => "Allergik rinit davolash:\n• Allergenni aniqlang (gul changi, chang, hayvon, oziq-ovqat)\n• Antihistamin dorilar (loratadin, tsetirizin)\n• Burun spreylari — shifokor buyurtmasi bilan\n• Uy tozaligiga ahamiyat bering — changni kamroq bo'lsin\n• Mavsumiy allergiyada oldindan profilaktik dori boshlang"],

            ['disease' => 'Krapivnitsa', 'doctors' => ['Allergolog', 'Dermatolog'], 'priority' => 9,
             'text' => "Krapivnitsa davolash:\n• Allergenni aniqlang va yo'q qiling\n• Antihistamin dorilar (loratadin, tsetirizin, desloratadin)\n• Qichigan joyni sovuq kompres bilan bosing\n• Keng, yumshoq kiyim kiying\n• ⚠️ Lab, til shishsa yoki nafas qiyinlashsa — DARHOL 103! (Kvinke shishi)"],

            ['disease' => 'COVID-19', 'doctors' => ['Infeksionist', 'Terapevt', 'Pulmonolog'], 'priority' => 13,
             'text' => "COVID-19 davolash:\n• Izolyatsiya (boshqalarga yuqtirmaslik)\n• Ko'p suyuqlik ichish\n• Harorat 38.5°C dan oshsa — paratsetamol\n• Kislorod saturatsiyasini kuzatish (95% dan past bo'lsa — shifoxonaga)\n• Nafas mashqlari — qorin bilan nafas olish\n• Surunkali kasalligi bo'lganlarda xavfli — zudlik bilan shifokor nazoratiga oling"],

            ['disease' => 'Gepatit A', 'doctors' => ['Infeksionist', 'Gastroenterolog'], 'priority' => 11,
             'text' => "Gepatit A davolash:\n• Dam olish, karavotda yotish\n• Qattiq parhez — yog'li, qovurilgan, achchiq taqiqlangan\n• Spirtli ichimliklar TO'LIQ taqiqlangan\n• Ko'p suyuqlik ichish\n• Shifokor nazoratida jigar funktsiyasi tekshiriladi\n• Odatda 2-6 haftada o'z-o'zidan tuzaladi, lekin nazorat shart"],

            ['disease' => 'Qizamiq', 'doctors' => ['Pediatr', 'Infeksionist'], 'priority' => 12,
             'text' => "Qizamiq davolash:\n• Izolyatsiya — juda yuqumli kasallik!\n• Dam olish, iliq suyuqlik ko'p ichish\n• Haroratni paratsetamol bilan tushirish\n• Ko'z yallig'lanishida — ko'zni iliq suv bilan yuvish\n• Asoratlar (pnevmoniya, ensefalit) xavfli — shifokor nazoratida bo'ling\n• Eng yaxshi himoya — emlash!"],

            ['disease' => 'Suvchechak (Vetryanka)', 'doctors' => ['Pediatr', 'Infeksionist'], 'priority' => 10,
             'text' => "Suvchechak davolash:\n• Qichishni antihistaminlar bilan kamaytiring\n• Pufakchalarni QASHLAMANG — iz qoladi va infeksiya tushadi\n• Har kuni toza kiyim va choyshab almashtiring\n• Brilliant yashil (zelyonka) yoki kalamin losyon surting\n• Haroratni paratsetamol bilan tushiring (aspirin BERMANG!)\n• Sog'lom bolalardan ajrating — juda yuqumli"],

            ['disease' => 'Depressiya', 'doctors' => ['Psixiatr', 'Nevrolog'], 'priority' => 11,
             'text' => "Depressiya davolash:\n• Shifokor (psixiatr yoki psixoterapevt) maslahatiga murojaat qiling\n• Antidepressantlar — FAQAT vrach buyurtmasi bilan\n• Psixoterapiya — muhim davolash usuli\n• Jismoniy faollik — tabbiiy antidepressant\n• Kundalik tartibga rioya qiling\n• Yaqinlaringiz bilan muloqotni uzmang\n• ⚠️ O'z-o'ziga zarar yetkazish fikrlari bo'lsa — DARHOL 103 yoki 1050 (ishonch telefoni)"],
        ];

        foreach ($recommendations as $recData) {
            $disease = $diseaseModels->firstWhere('name', $recData['disease']);
            if (!$disease) continue;

            $matchingDoctors = $findDoctors(...$recData['doctors']);

            if ($matchingDoctors->isEmpty()) {
                // Create recommendation without doctor
                Recommendation::firstOrCreate(
                    ['disease_id' => $disease->id, 'recommendation_text' => $recData['text']],
                    ['priority' => $recData['priority'], 'is_active' => true]
                );
            } else {
                foreach ($matchingDoctors as $doctor) {
                    Recommendation::firstOrCreate(
                        ['disease_id' => $disease->id, 'doctor_id' => $doctor->id],
                        ['recommendation_text' => $recData['text'], 'priority' => $recData['priority'], 'is_active' => true]
                    );
                }
            }
        }

        // ─── BANNERS (4) ──────────────────────────────────────────────
        $bannersData = [
            ['title' => 'QuickMedAI — Sog\'liq yordamchingiz', 'description' => 'Sun\'iy intellekt yordamida tez va qulay tibbiy maslahat oling. 24/7 ishlaydi!', 'media_type' => 'text', 'position' => 'sidebar', 'sort_order' => 1],
            ['title' => 'Shifokorga yoziling', 'description' => 'Online navbat orqali o\'zingizga qulay vaqtda shifokorga yoziling. Kutmasdan, navbatsiz!', 'media_type' => 'text', 'position' => 'sidebar', 'sort_order' => 2],
            ['title' => 'Tekshiruvdan o\'ting', 'description' => 'Yiliga kamida 1 marta to\'liq tibbiy tekshiruvdan o\'tish tavsiya etiladi. Sog\'liq — boylik!', 'media_type' => 'text', 'position' => 'sidebar', 'sort_order' => 3],
            ['title' => 'Vaksinatsiya muhim!', 'description' => 'Bolangizni o\'z vaqtida emlang. Qizamiq, poliomielit va boshqa xavfli kasalliklardan himoya.', 'media_type' => 'text', 'position' => 'sidebar', 'sort_order' => 4],
        ];

        foreach ($bannersData as $banner) {
            Banner::firstOrCreate(
                ['title' => $banner['title']],
                array_merge($banner, ['is_active' => true])
            );
        }

        $this->command->info('✅ 6 ta klinika, 35 ta shifokor, 40 ta kasallik, 40+ tavsiya, 4 ta banner yaratildi!');
    }
}
