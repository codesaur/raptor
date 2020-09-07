<?php namespace Indoraptor\Models;

class Initial
{
    public static function reference($m) {
    // model: ReferenceModel
    // table: reference
        $m->replaces(['lookup' => 'lookup_country', 'Model' => 'CountryModel'], ['en' => ['title' => 'World countries'], 'mn' => ['title' => 'Дэлхийн улсууд']], 'lookup');
        $m->replaces(['lookup' => 'lookup_gender', 'Model' => 'LookupModel'], ['en' => ['title' => 'Gender'], 'mn' => ['title' => 'Хүйс']], 'lookup');
        $m->replaces(['lookup' => 'lookup_record_type', 'Model' => 'LookupModel'], ['en' => ['title' => 'Types of record'], 'mn' => ['title' => 'Бичлэгийн төрөл']], 'lookup');
        $m->replaces(['lookup' => 'lookup_status', 'Model' => 'LookupModel'], ['en' => ['title' => 'Status'], 'mn' => ['title' => 'Төлөв']], 'lookup');
        $m->replaces(['lookup' => 'lookup_template_type', 'Model' => 'LookupModel'], ['en' => ['title' => 'Template types'], 'mn' => ['title' => 'Загварын төрөл']], 'lookup');
        $m->replaces(['lookup' => 'lookup_page_types', 'Model' => 'LookupModel'], ['en' => ['title' => 'Content Types'], 'mn' => ['title' => 'Агуулгын төрөл']], 'lookup');
        $m->replaces(['lookup' => 'lookup_menu_css_types', 'Model' => 'LookupModel'], ['en' => ['title' => 'Menu CSS Types'], 'mn' => ['title' => 'Цэсний хэлбэр']], 'lookup');
        $m->replaces(['lookup' => 'lookup_news_types', 'Model' => 'LookupModel'], ['mn' => ['title' => 'Мэдээний төрөл'], 'en' => ['title' => 'News Types']], 'lookup');
        $m->replaces(['lookup' => 'lookup_news_category', 'Model' => 'LookupModel'], ['en' => ['title' => 'News category'], 'mn' => ['title' => 'Мэдээний ангилал']], 'lookup');
        $m->replaces(['lookup' => 'lookup_blog_style', 'Model' => 'LookupModel'], ['en' => ['title' => 'Blog style'], 'mn' => ['title' => 'Блог загвар']], 'lookup');
    }
    
    public static function lookup_news_types($m) {
    // model: LookupModel
    // table: lookup_news_types
        $m->replaces(['_keyword_' => 'general'], ['en' => ['title' => 'General'], 'mn' => ['title' => 'Ерөнхий']]);
        $m->replaces(['_keyword_' => 'video'], ['mn' => ['title' => 'Видео'], 'en' => ['title' => 'Video']]);
        $m->replaces(['_keyword_' => 'announcement'], ['en' => ['title' => 'Announcement'], 'mn' => ['title' => 'Зар мэдээлэл']]);
    }
    
    public static function lookup_news_category($m) {
    // model: LookupModel
    // table: lookup_news_category
        $m->replaces(['_keyword_' => 'common', 'type' => 0], ['en' => ['title' => 'Common'], 'mn' => ['title' => 'Нийтлэг']]);
        $m->replaces(['_keyword_' => 'featured', 'type' => 0], ['mn' => ['title' => 'Онцлох'], 'en' => ['title' => 'Featured']]);
        $m->replaces(['_keyword_' => 'hot', 'type' => 0], ['en' => ['title' => 'Hot'], 'mn' => ['title' => 'Халуун']]);
        $m->replaces(['_keyword_' => 'fun', 'type' => 0], ['en' => ['title' => 'Fun'], 'mn' => ['title' => 'Хөгжилтэй']]);
    }
    
    public static function lookup_blog_style($m) {
    // model: LookupModel
    // table: lookup_blog_style
        $m->replaces(['_keyword_' => 'normal', 'type' => 0], ['en' => ['title' => 'Normal'], 'mn' => ['title' => 'Энгийн']]);
    }
    
    public static function lookup_gender($m) {
    // model: LookupModel
    // table: lookup_gender
        $m->replaces(['_keyword_' => 'male'], ['mn' => ['title' => 'Эрэгтэй'], 'en' => ['title' => 'Male']]);
        $m->replaces(['_keyword_' => 'female'], ['mn' => ['title' => 'Эмэгтэй'], 'en' => ['title' => 'Female']]);
    }
    
    public static function lookup_template_type($m) {
    // model: LookupModel
    // table: lookup_template_type
        $m->replaces(['_keyword_' => '1'], ['mn' => ['title' => 'Ерөнхий'], 'en' => ['title' => 'General']]);
        $m->replaces(['_keyword_' => '2'], ['mn' => ['title' => 'Систем'], 'en' => ['title' => 'System']]);
        $m->replaces(['_keyword_' => '3'], ['mn' => ['title' => 'Заавар'], 'en' => ['title' => 'Manual']]);
        $m->replaces(['_keyword_' => '4'], ['mn' => ['title' => 'Сонордуулга'], 'en' => ['title' => 'Notification']]);
        $m->replaces(['_keyword_' => '5'], ['mn' => ['title' => 'Цахим захиа'], 'en' => ['title' => 'Email']]);
    }
    
    public static function lookup_status($m) {
    // model: LookupModel
    // table: lookup_status
        $m->replaces(['_keyword_' => '1'], ['mn' => ['title' => 'Идэвхтэй'], 'en' => ['title' => 'Active']]);
        $m->replaces(['_keyword_' => '0'], ['mn' => ['title' => 'Идэвхгүй'], 'en' => ['title' => 'Inactive']]);
    }
    
    public static function lookup_record_type($m) {
    // model: LookupModel
    // table: lookup_record_type
        $m->replaces(['_keyword_' => '0'], ['mn' => ['title' => 'sys-defined'], 'en' => ['title' => 'sys-defined']]);
        $m->replaces(['_keyword_' => '1'], ['mn' => ['title' => 'user-defined'], 'en' => ['title' => 'user-defined']]);
    }
    
    public static function lookup_page_types($m) {
    // model: LookupModel
    // table: lookup_page_types
        $m->replaces(['_keyword_' => 'menu'], ['en' => ['title' => 'Menu'], 'mn' => ['title' => 'Цэс']]);
        $m->replaces(['_keyword_' => 'special'], ['en' => ['title' => 'Special'], 'mn' => ['title' => 'Тусгай']]);
    }
    
    public static function lookup_menu_css_types($m) {
    // model: LookupModel
    // table: lookup_menu_css_types
        $m->replaces(['_keyword_' => 'default'], ['en' => ['title' => 'Default'], 'mn' => ['title' => 'Энгийн']]);
        $m->replaces(['_keyword_' => 'dropdown'], ['en' => ['title' => 'Dropdown Menu'], 'mn' => ['title' => 'Унжих цэс']]);
        $m->replaces(['_keyword_' => 'megamenu'], ['en' => ['title' => 'Mega Menu'], 'mn' => ['title' => 'Мега цэс']]);
        $m->replaces(['_keyword_' => 'megamenu-content'], ['en' => ['title' => 'Mega menu content'], 'mn' => ['title' => 'Мега цэсний контент']]);
    }
    
    public static function lookup_countries($m) {
    // model: Velociraptor\CountryModel
    // table: lookup_countries
        $m->replaces(array('code' => 'AD', 'speak' => 'Català'), array('mn' => array('title' => 'Андорра'), 'en' => array('title' => 'Andorra')), 'code');
        $m->replaces(array('code' => 'AE', 'speak' => 'الإمارات العربية المتحدة'), array('en' => array('title' => 'United Arab Emirates'), 'mn' => array('title' => 'Арабын Нэгдсэн Эмират')), 'code');
        $m->replaces(array('code' => 'AF', 'speak' => 'د افغانستان اسلامي جمهوریت'), array('en' => array('title' => 'Afghanistan'), 'mn' => array('title' => 'Афганистан')), 'code');
        $m->replaces(array('code' => 'AI', 'speak' => 'English'), array('mn' => array('title' => 'Anguilla'), 'en' => array('title' => 'Anguilla')), 'code');
        $m->replaces(array('code' => 'AL', 'speak' => 'Shqip'), array('en' => array('title' => 'Albania'), 'mn' => array('title' => 'Албани')), 'code');
        $m->replaces(array('code' => 'AM', 'speak' => 'Armenian'), array('en' => array('title' => 'Armenia'), 'mn' => array('title' => 'Армен')), 'code');
        $m->replaces(array('code' => 'AN', 'speak' => 'Curaçao'), array('mn' => array('title' => 'Нидерландын Антилийн арлууд'), 'en' => array('title' => 'Netherlands Antilles')), 'code');
        $m->replaces(array('code' => 'AO', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Ангол'), 'en' => array('title' => 'Angola')), 'code');
        $m->replaces(array('code' => 'AQ', 'speak' => 'English'), array('en' => array('title' => 'Antarctica'), 'mn' => array('title' => 'Антарктик')), 'code');
        $m->replaces(array('code' => 'AR', 'speak' => 'Spanish'), array('mn' => array('title' => 'Аргентин'), 'en' => array('title' => 'Argentina')), 'code');
        $m->replaces(array('code' => 'AS', 'speak' => 'Sāmoa'), array('mn' => array('title' => 'American Samoa'), 'en' => array('title' => 'American Samoa')), 'code');
        $m->replaces(array('code' => 'AT', 'speak' => 'Deutsch'), array('mn' => array('title' => 'Австри'), 'en' => array('title' => 'Austria')), 'code');
        $m->replaces(array('code' => 'AU', 'speak' => 'English'), array('mn' => array('title' => 'Автрали'), 'en' => array('title' => 'Australia')), 'code');
        $m->replaces(array('code' => 'AW', 'speak' => 'Dutch'), array('mn' => array('title' => 'Аруба'), 'en' => array('title' => 'Aruba')), 'code');
        $m->replaces(array('code' => 'AZ', 'speak' => 'Azerbaijani'), array('en' => array('title' => 'Azerbaijan'), 'mn' => array('title' => 'Азербайжан')), 'code');
        $m->replaces(array('code' => 'BA', 'speak' => 'Bosnian, Croatian and Serbian '), array('mn' => array('title' => 'Босни Херцеговин'), 'en' => array('title' => 'Bosnia and Herzegowina')), 'code');
        $m->replaces(array('code' => 'BB', 'speak' => 'English'), array('mn' => array('title' => 'Барбадос'), 'en' => array('title' => 'Barbados')), 'code');
        $m->replaces(array('code' => 'BD', 'speak' => 'Bengali'), array('en' => array('title' => 'Bangladesh'), 'mn' => array('title' => 'Бангладеш')), 'code');
        $m->replaces(array('code' => 'BE', 'speak' => 'French, Dutch, German'), array('en' => array('title' => 'Belgium'), 'mn' => array('title' => 'Бельги')), 'code');
        $m->replaces(array('code' => 'BF', 'speak' => 'Burkina Faso'), array('mn' => array('title' => 'Буркина Фасо'), 'en' => array('title' => 'Burkina Faso')), 'code');
        $m->replaces(array('code' => 'BG', 'speak' => 'Bulgarian'), array('en' => array('title' => 'Bulgaria'), 'mn' => array('title' => 'Болгар')), 'code');
        $m->replaces(array('code' => 'BH', 'speak' => 'Arabic'), array('en' => array('title' => 'Bahrain'), 'mn' => array('title' => 'Бахрейн')), 'code');
        $m->replaces(array('code' => 'BI', 'speak' => 'French, Kirund'), array('mn' => array('title' => 'Бурунди'), 'en' => array('title' => 'Burundi')), 'code');
        $m->replaces(array('code' => 'BJ', 'speak' => 'French'), array('mn' => array('title' => 'Бенин'), 'en' => array('title' => 'Benin')), 'code');
        $m->replaces(array('code' => 'BM', 'speak' => 'Bermud üçbucağ'), array('en' => array('title' => 'Bermuda'), 'mn' => array('title' => 'Бермуд')), 'code');
        $m->replaces(array('code' => 'BN', 'speak' => 'Malay'), array('mn' => array('title' => 'Бруней'), 'en' => array('title' => 'Brunei Darussalam')), 'code');
        $m->replaces(array('code' => 'BO', 'speak' => 'Spanish, Aymara, Chiquitano'), array('mn' => array('title' => 'Болив'), 'en' => array('title' => 'Bolivia')), 'code');
        $m->replaces(array('code' => 'BR', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Бразил'), 'en' => array('title' => 'Brazil')), 'code');
        $m->replaces(array('code' => 'BS', 'speak' => 'English'), array('mn' => array('title' => 'Бахам'), 'en' => array('title' => 'Bahamas')), 'code');
        $m->replaces(array('code' => 'BT', 'speak' => 'Dzongkha'), array('mn' => array('title' => 'Бутан'), 'en' => array('title' => 'Bhutan')), 'code');
        $m->replaces(array('code' => 'BV', 'speak' => 'Norwegia'), array('en' => array('title' => 'Bouvet Island'), 'mn' => array('title' => 'Bouvet Island')), 'code');
        $m->replaces(array('code' => 'BW', 'speak' => 'English, Tswana'), array('mn' => array('title' => 'Ботсван'), 'en' => array('title' => 'Botswana')), 'code');
        $m->replaces(array('code' => 'BY', 'speak' => 'Belarusian'), array('mn' => array('title' => 'Беларусь'), 'en' => array('title' => 'Belarus')), 'code');
        $m->replaces(array('code' => 'BZ', 'speak' => 'English'), array('en' => array('title' => 'Belize'), 'mn' => array('title' => 'Белиз')), 'code');
        $m->replaces(array('code' => 'CA', 'speak' => 'English'), array('en' => array('title' => 'Canada'), 'mn' => array('title' => 'Канад')), 'code');
        $m->replaces(array('code' => 'CC', 'speak' => 'English'), array('mn' => array('title' => 'Cocos (Keeling) Islands'), 'en' => array('title' => 'Cocos (Keeling) Islands')), 'code');
        $m->replaces(array('code' => 'CD', 'speak' => 'French'), array('en' => array('title' => 'Congo, the Democratic Republic of the'), 'mn' => array('title' => 'Бүгд Найрамдах Ардчилсан Конго')), 'code');
        $m->replaces(array('code' => 'CF', 'speak' => 'Sango, French'), array('en' => array('title' => 'Central African Republic'), 'mn' => array('title' => 'Төв Африк')), 'code');
        $m->replaces(array('code' => 'CG', 'speak' => 'French'), array('mn' => array('title' => 'Конго'), 'en' => array('title' => 'Congo')), 'code');
        $m->replaces(array('code' => 'CH', 'speak' => 'French, German, Italian, Romans'), array('mn' => array('title' => 'Швейцарь'), 'en' => array('title' => 'Switzerland')), 'code');
        $m->replaces(array('code' => 'CI', 'speak' => 'French'), array('en' => array('title' => 'Cote d\'Ivoire'), 'mn' => array('title' => 'Кот Дивуар')), 'code');
        $m->replaces(array('code' => 'CK', 'speak' => 'English, Rarotongan'), array('mn' => array('title' => 'Cook Islands'), 'en' => array('title' => 'Cook Islands')), 'code');
        $m->replaces(array('code' => 'CL', 'speak' => 'Spanish'), array('mn' => array('title' => 'Чили'), 'en' => array('title' => 'Chile')), 'code');
        $m->replaces(array('code' => 'CM', 'speak' => 'French, English'), array('mn' => array('title' => 'Камерун'), 'en' => array('title' => 'Cameroon')), 'code');
        $m->replaces(array('code' => 'CN', 'speak' => '中文'), array('mn' => array('title' => 'Хятад'), 'en' => array('title' => 'China')), 'code');
        $m->replaces(array('code' => 'CO', 'speak' => 'Spanish'), array('mn' => array('title' => 'Колумб'), 'en' => array('title' => 'Colombia')), 'code');
        $m->replaces(array('code' => 'CR', 'speak' => 'Spanish'), array('en' => array('title' => 'Costa Rica'), 'mn' => array('title' => 'Коста рика')), 'code');
        $m->replaces(array('code' => 'CU', 'speak' => 'Spanish'), array('mn' => array('title' => 'Куба'), 'en' => array('title' => 'Cuba')), 'code');
        $m->replaces(array('code' => 'CV', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Кабо Верде'), 'en' => array('title' => 'Cape Verde')), 'code');
        $m->replaces(array('code' => 'CX', 'speak' => 'English'), array('en' => array('title' => 'Christmas Island'), 'mn' => array('title' => 'Christmas Island')), 'code');
        $m->replaces(array('code' => 'CY', 'speak' => 'Turkish, Greek'), array('en' => array('title' => 'Cyprus'), 'mn' => array('title' => 'Кибр')), 'code');
        $m->replaces(array('code' => 'CZ', 'speak' => 'Čeština'), array('mn' => array('title' => 'Чех'), 'en' => array('title' => 'Czech Republic')), 'code');
        $m->replaces(array('code' => 'DE', 'speak' => 'Deutsch'), array('en' => array('title' => 'Germany'), 'mn' => array('title' => 'Герман')), 'code');
        $m->replaces(array('code' => 'DJ', 'speak' => 'French, Arabic'), array('mn' => array('title' => 'Djibouti'), 'en' => array('title' => 'Djibouti')), 'code');
        $m->replaces(array('code' => 'DK', 'speak' => 'Danish'), array('mn' => array('title' => 'Дани'), 'en' => array('title' => 'Denmark')), 'code');
        $m->replaces(array('code' => 'DM', 'speak' => 'English'), array('mn' => array('title' => 'Доминик'), 'en' => array('title' => 'Dominica')), 'code');
        $m->replaces(array('code' => 'DO', 'speak' => 'Spanish'), array('mn' => array('title' => 'Доминикан'), 'en' => array('title' => 'Dominican Republic')), 'code');
        $m->replaces(array('code' => 'DZ', 'speak' => 'Arabic, Berber'), array('mn' => array('title' => 'Алжир'), 'en' => array('title' => 'Algeria')), 'code');
        $m->replaces(array('code' => 'EC', 'speak' => 'Spanish'), array('mn' => array('title' => 'Эквадор'), 'en' => array('title' => 'Ecuador')), 'code');
        $m->replaces(array('code' => 'EE', 'speak' => 'Estonian'), array('mn' => array('title' => 'Эстони'), 'en' => array('title' => 'Estonia')), 'code');
        $m->replaces(array('code' => 'EG', 'speak' => 'Arabic'), array('mn' => array('title' => 'Египет'), 'en' => array('title' => 'Egypt')), 'code');
        $m->replaces(array('code' => 'EH', 'speak' => 'Arabic'), array('mn' => array('title' => 'Баруун Сахар'), 'en' => array('title' => 'Western Sahara')), 'code');
        $m->replaces(array('code' => 'ER', 'speak' => ''), array('en' => array('title' => 'Eritrea'), 'mn' => array('title' => 'Эквадор')), 'code');
        $m->replaces(array('code' => 'ES', 'speak' => 'España'), array('en' => array('title' => 'Spain'), 'mn' => array('title' => 'Испани')), 'code');
        $m->replaces(array('code' => 'ET', 'speak' => ''), array('mn' => array('title' => 'Этиоп'), 'en' => array('title' => 'Ethiopia')), 'code');
        $m->replaces(array('code' => 'FI', 'speak' => 'Finnish '), array('en' => array('title' => 'Finland'), 'mn' => array('title' => 'Финланд')), 'code');
        $m->replaces(array('code' => 'FJ', 'speak' => 'English, Fijian'), array('en' => array('title' => 'Fiji'), 'mn' => array('title' => 'Фижи')), 'code');
        $m->replaces(array('code' => 'FK', 'speak' => 'English'), array('mn' => array('title' => 'Falkland Islands (Malvinas)'), 'en' => array('title' => 'Falkland Islands (Malvinas)')), 'code');
        $m->replaces(array('code' => 'FM', 'speak' => 'English'), array('en' => array('title' => 'Micronesia, Federated States of'), 'mn' => array('title' => 'Микронез')), 'code');
        $m->replaces(array('code' => 'FO', 'speak' => 'Danish, Faroese'), array('mn' => array('title' => 'Faroe Islands'), 'en' => array('title' => 'Faroe Islands')), 'code');
        $m->replaces(array('code' => 'FR', 'speak' => 'French'), array('mn' => array('title' => 'Франц'), 'en' => array('title' => 'France')), 'code');
        $m->replaces(array('code' => 'GA', 'speak' => 'French, ɡabɔ̃'), array('mn' => array('title' => 'Габон'), 'en' => array('title' => 'Gabon')), 'code');
        $m->replaces(array('code' => 'GB', 'speak' => 'British English'), array('mn' => array('title' => 'Их Британи'), 'en' => array('title' => 'United Kingdom')), 'code');
        $m->replaces(array('code' => 'GD', 'speak' => 'English'), array('mn' => array('title' => 'Гренада'), 'en' => array('title' => 'Grenada')), 'code');
        $m->replaces(array('code' => 'GE', 'speak' => 'Georgian: საქართველო'), array('mn' => array('title' => 'Гүрж'), 'en' => array('title' => 'Georgia')), 'code');
        $m->replaces(array('code' => 'GF', 'speak' => 'Guyane française'), array('mn' => array('title' => 'Францын Гвиней'), 'en' => array('title' => 'French Guiana')), 'code');
        $m->replaces(array('code' => 'GH', 'speak' => 'English'), array('mn' => array('title' => 'Гана'), 'en' => array('title' => 'Ghana')), 'code');
        $m->replaces(array('code' => 'GI', 'speak' => 'English'), array('mn' => array('title' => 'Гибралтар'), 'en' => array('title' => 'Gibraltar')), 'code');
        $m->replaces(array('code' => 'GL', 'speak' => 'Greenlandic'), array('en' => array('title' => 'Greenland'), 'mn' => array('title' => 'Гренланд')), 'code');
        $m->replaces(array('code' => 'GM', 'speak' => 'English'), array('en' => array('title' => 'Gambia'), 'mn' => array('title' => 'Гамби')), 'code');
        $m->replaces(array('code' => 'GN', 'speak' => 'French'), array('mn' => array('title' => 'Гвиней'), 'en' => array('title' => 'Guinea')), 'code');
        $m->replaces(array('code' => 'GP', 'speak' => 'French'), array('en' => array('title' => 'Guadeloupe'), 'mn' => array('title' => 'Guadeloupe')), 'code');
        $m->replaces(array('code' => 'GQ', 'speak' => ''), array('en' => array('title' => 'Equatorial Guinea'), 'mn' => array('title' => 'Экваторын Гвиней')), 'code');
        $m->replaces(array('code' => 'GR', 'speak' => 'Greek: Ελλάδα'), array('mn' => array('title' => 'Грек'), 'en' => array('title' => 'Greece')), 'code');
        $m->replaces(array('code' => 'GS', 'speak' => 'English'), array('en' => array('title' => 'South Georgia and the South Sandwich Islands'), 'mn' => array('title' => 'South Georgia and the South Sandwich Islands')), 'code');
        $m->replaces(array('code' => 'GT', 'speak' => ''), array('mn' => array('title' => 'Guatemala'), 'en' => array('title' => 'Guatemala')), 'code');
        $m->replaces(array('code' => 'GU', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Гуам'), 'en' => array('title' => 'Guam')), 'code');
        $m->replaces(array('code' => 'GW', 'speak' => ''), array('mn' => array('title' => 'Guinea-Bissau'), 'en' => array('title' => 'Guinea-Bissau')), 'code');
        $m->replaces(array('code' => 'GY', 'speak' => 'English'), array('mn' => array('title' => 'Гайана'), 'en' => array('title' => 'Guyana')), 'code');
        $m->replaces(array('code' => 'HK', 'speak' => 'English, Chinese'), array('mn' => array('title' => 'Хонг Гонг'), 'en' => array('title' => 'Hong Kong')), 'code');
        $m->replaces(array('code' => 'HM', 'speak' => 'Australia'), array('mn' => array('title' => 'Мк Доналдын арлууд'), 'en' => array('title' => 'Heard and Mc Donald Islands')), 'code');
        $m->replaces(array('code' => 'HN', 'speak' => 'Spanish'), array('mn' => array('title' => 'Гондурас'), 'en' => array('title' => 'Honduras')), 'code');
        $m->replaces(array('code' => 'HR', 'speak' => 'Hrvatska'), array('mn' => array('title' => 'Хорват'), 'en' => array('title' => 'Croatia (Hrvatska)')), 'code');
        $m->replaces(array('code' => 'HT', 'speak' => 'French Haitian Creole'), array('mn' => array('title' => 'Гайти'), 'en' => array('title' => 'Haiti')), 'code');
        $m->replaces(array('code' => 'HU', 'speak' => 'Hungarian: Magyarország'), array('en' => array('title' => 'Hungary'), 'mn' => array('title' => 'Унгари')), 'code');
        $m->replaces(array('code' => 'ID', 'speak' => 'Indonesian'), array('en' => array('title' => 'Indonesia'), 'mn' => array('title' => 'Индонези')), 'code');
        $m->replaces(array('code' => 'IE', 'speak' => 'English, Irish, Ulster Scots'), array('mn' => array('title' => 'Ирланд'), 'en' => array('title' => 'Ireland')), 'code');
        $m->replaces(array('code' => 'IL', 'speak' => 'Hebrew, Arabic'), array('en' => array('title' => 'Israel'), 'mn' => array('title' => 'Израйл')), 'code');
        $m->replaces(array('code' => 'IN', 'speak' => 'Hindi, English'), array('en' => array('title' => 'India'), 'mn' => array('title' => 'Энэтхэг')), 'code');
        $m->replaces(array('code' => 'IO', 'speak' => 'English'), array('mn' => array('title' => 'Их Британийн Энэтхэгийн далайн нутаг'), 'en' => array('title' => 'British Indian Ocean Territory')), 'code');
        $m->replaces(array('code' => 'IQ', 'speak' => 'Arabic Kurdish'), array('en' => array('title' => 'Iraq'), 'mn' => array('title' => 'Ирак')), 'code');
        $m->replaces(array('code' => 'IR', 'speak' => 'Persian'), array('mn' => array('title' => 'Иран'), 'en' => array('title' => 'Iran (Islamic Republic of)')), 'code');
        $m->replaces(array('code' => 'IS', 'speak' => 'Icelandic'), array('mn' => array('title' => 'Исланд'), 'en' => array('title' => 'Iceland')), 'code');
        $m->replaces(array('code' => 'IT', 'speak' => 'Italiana'), array('mn' => array('title' => 'Итали'), 'en' => array('title' => 'Italy')), 'code');
        $m->replaces(array('code' => 'JM', 'speak' => 'English'), array('mn' => array('title' => 'Ямайка'), 'en' => array('title' => 'Jamaica')), 'code');
        $m->replaces(array('code' => 'JO', 'speak' => 'Arabic: الأردن'), array('mn' => array('title' => 'Иордан'), 'en' => array('title' => 'Jordan')), 'code');
        $m->replaces(array('code' => 'JP', 'speak' => ''), array('mn' => array('title' => 'Япон'), 'en' => array('title' => 'Japan')), 'code');
        $m->replaces(array('code' => 'KE', 'speak' => 'Swahili, English'), array('mn' => array('title' => 'Кень'), 'en' => array('title' => 'Kenya')), 'code');
        $m->replaces(array('code' => 'KG', 'speak' => 'Kyrgyz'), array('en' => array('title' => 'Kyrgyzstan'), 'mn' => array('title' => 'Киргестан')), 'code');
        $m->replaces(array('code' => 'KH', 'speak' => 'Khmer'), array('mn' => array('title' => 'Камбож'), 'en' => array('title' => 'Cambodia')), 'code');
        $m->replaces(array('code' => 'KI', 'speak' => 'English Gilbertese'), array('en' => array('title' => 'Kiribati'), 'mn' => array('title' => 'Кирибати')), 'code');
        $m->replaces(array('code' => 'KM', 'speak' => 'Comorian, Arabic, French'), array('en' => array('title' => 'Comoros'), 'mn' => array('title' => 'Коморын арал')), 'code');
        $m->replaces(array('code' => 'KN', 'speak' => 'English'), array('mn' => array('title' => 'Сент Китс Невисийн Холбооны'), 'en' => array('title' => 'Saint Kitts and Nevis')), 'code');
        $m->replaces(array('code' => 'KP', 'speak' => '조선말'), array('en' => array('title' => 'Korea, Democratic People\'s Republic of'), 'mn' => array('title' => 'Умард Солонгос')), 'code');
        $m->replaces(array('code' => 'KR', 'speak' => '한국어'), array('en' => array('title' => 'Korea, Republic of'), 'mn' => array('title' => 'Өмнөд Солонгос')), 'code');
        $m->replaces(array('code' => 'KW', 'speak' => 'Arabic'), array('mn' => array('title' => 'Кувейт'), 'en' => array('title' => 'Kuwait')), 'code');
        $m->replaces(array('code' => 'KY', 'speak' => 'English'), array('en' => array('title' => 'Cayman Islands'), 'mn' => array('title' => 'Кайманы арлууд')), 'code');
        $m->replaces(array('code' => 'KZ', 'speak' => '‎Kazakh'), array('mn' => array('title' => 'Казакстан'), 'en' => array('title' => 'Kazakhstan')), 'code');
        $m->replaces(array('code' => 'LA', 'speak' => 'Lao'), array('mn' => array('title' => 'Лаос'), 'en' => array('title' => 'Lao People\'s Democratic Republic')), 'code');
        $m->replaces(array('code' => 'LB', 'speak' => 'Arabic'), array('mn' => array('title' => 'Ливан'), 'en' => array('title' => 'Lebanon')), 'code');
        $m->replaces(array('code' => 'LC', 'speak' => 'English'), array('mn' => array('title' => 'Сент-Люси'), 'en' => array('title' => 'Saint LUCIA')), 'code');
        $m->replaces(array('code' => 'LI', 'speak' => 'German'), array('mn' => array('title' => 'Лихтенштейн'), 'en' => array('title' => 'Liechtenstein')), 'code');
        $m->replaces(array('code' => 'LK', 'speak' => 'Sinhala, Tamil, English'), array('mn' => array('title' => 'Шри Ланка'), 'en' => array('title' => 'Sri Lanka')), 'code');
        $m->replaces(array('code' => 'LR', 'speak' => 'English'), array('mn' => array('title' => 'Ливери'), 'en' => array('title' => 'Liberia')), 'code');
        $m->replaces(array('code' => 'LS', 'speak' => 'English, Southern Sotho'), array('en' => array('title' => 'Lesotho'), 'mn' => array('title' => 'Лесото')), 'code');
        $m->replaces(array('code' => 'LT', 'speak' => '‎Lithuanian'), array('mn' => array('title' => 'Литва'), 'en' => array('title' => 'Lithuania')), 'code');
        $m->replaces(array('code' => 'LU', 'speak' => 'German, French, Luxembourgish'), array('en' => array('title' => 'Luxembourg'), 'mn' => array('title' => 'Люксембург')), 'code');
        $m->replaces(array('code' => 'LV', 'speak' => '‎Latvian'), array('en' => array('title' => 'Latvia'), 'mn' => array('title' => 'Латви')), 'code');
        $m->replaces(array('code' => 'LY', 'speak' => 'Arabic'), array('mn' => array('title' => 'Ливи'), 'en' => array('title' => 'Libyan Arab Jamahiriya')), 'code');
        $m->replaces(array('code' => 'MA', 'speak' => 'Arabic'), array('en' => array('title' => 'Morocco'), 'mn' => array('title' => 'Марокко')), 'code');
        $m->replaces(array('code' => 'MC', 'speak' => 'French'), array('en' => array('title' => 'Monaco'), 'mn' => array('title' => 'Монако')), 'code');
        $m->replaces(array('code' => 'MD', 'speak' => '‎Moldovan'), array('mn' => array('title' => 'Молдав'), 'en' => array('title' => 'Moldova, Republic of')), 'code');
        $m->replaces(array('code' => 'MG', 'speak' => 'Malagasy, French'), array('en' => array('title' => 'Madagascar'), 'mn' => array('title' => 'Мадагаскар')), 'code');
        $m->replaces(array('code' => 'MH', 'speak' => 'English, Marshallese'), array('mn' => array('title' => 'Маршаллын арлууд'), 'en' => array('title' => 'Marshall Islands')), 'code');
        $m->replaces(array('code' => 'MK', 'speak' => 'Macedonian'), array('mn' => array('title' => 'Македон'), 'en' => array('title' => 'Macedonia, The Former Yugoslav Republic of')), 'code');
        $m->replaces(array('code' => 'ML', 'speak' => '‎Bambara'), array('mn' => array('title' => 'Мали'), 'en' => array('title' => 'Mali')), 'code');
        $m->replaces(array('code' => 'MM', 'speak' => 'Burmese'), array('mn' => array('title' => 'Мьянмар'), 'en' => array('title' => 'Myanmar')), 'code');
        $m->replaces(array('code' => 'MN', 'speak' => 'Монгол'), array('mn' => array('title' => 'Монгол'), 'en' => array('title' => 'Mongolia')), 'code');
        $m->replaces(array('code' => 'MO', 'speak' => 'Chinese'), array('mn' => array('title' => 'Макоа'), 'en' => array('title' => 'Macau')), 'code');
        $m->replaces(array('code' => 'MP', 'speak' => 'English, Chamorro'), array('mn' => array('title' => 'Өмнөд Маринагийн арлууд'), 'en' => array('title' => 'Northern Mariana Islands')), 'code');
        $m->replaces(array('code' => 'MQ', 'speak' => 'French'), array('en' => array('title' => 'Martinique'), 'mn' => array('title' => 'Martinique')), 'code');
        $m->replaces(array('code' => 'MR', 'speak' => 'Arabic'), array('mn' => array('title' => 'Мавритан'), 'en' => array('title' => 'Mauritania')), 'code');
        $m->replaces(array('code' => 'MS', 'speak' => 'English'), array('en' => array('title' => 'Montserrat'), 'mn' => array('title' => 'Монтенегро')), 'code');
        $m->replaces(array('code' => 'MT', 'speak' => 'English, Maltese'), array('en' => array('title' => 'Malta'), 'mn' => array('title' => 'Мальт')), 'code');
        $m->replaces(array('code' => 'MU', 'speak' => ''), array('mn' => array('title' => 'Mauritius'), 'en' => array('title' => 'Mauritius')), 'code');
        $m->replaces(array('code' => 'MV', 'speak' => ''), array('en' => array('title' => 'Maldives'), 'mn' => array('title' => 'Мальдив')), 'code');
        $m->replaces(array('code' => 'MW', 'speak' => 'English'), array('en' => array('title' => 'Malawi'), 'mn' => array('title' => 'Малави')), 'code');
        $m->replaces(array('code' => 'MX', 'speak' => 'Spanish'), array('mn' => array('title' => 'Мексик'), 'en' => array('title' => 'Mexico')), 'code');
        $m->replaces(array('code' => 'MY', 'speak' => '‎Malaysian'), array('en' => array('title' => 'Malaysia'), 'mn' => array('title' => 'Малайз')), 'code');
        $m->replaces(array('code' => 'MZ', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Мозамбик'), 'en' => array('title' => 'Mozambique')), 'code');
        $m->replaces(array('code' => 'NA', 'speak' => 'English, German'), array('mn' => array('title' => 'Намбиа'), 'en' => array('title' => 'Namibia')), 'code');
        $m->replaces(array('code' => 'NC', 'speak' => 'French'), array('mn' => array('title' => 'Шинэ Кальдониа'), 'en' => array('title' => 'New Caledonia')), 'code');
        $m->replaces(array('code' => 'NE', 'speak' => 'French'), array('mn' => array('title' => 'Нигер'), 'en' => array('title' => 'Niger')), 'code');
        $m->replaces(array('code' => 'NF', 'speak' => 'English, Norfuk'), array('mn' => array('title' => 'Норфолк'), 'en' => array('title' => 'Norfolk Island')), 'code');
        $m->replaces(array('code' => 'NG', 'speak' => 'English, Hausa, Yoruba, Igbo'), array('mn' => array('title' => 'Нигери'), 'en' => array('title' => 'Nigeria')), 'code');
        $m->replaces(array('code' => 'NI', 'speak' => 'Spanish'), array('mn' => array('title' => 'Никарагуа'), 'en' => array('title' => 'Nicaragua')), 'code');
        $m->replaces(array('code' => 'NL', 'speak' => 'Dutch, Frisian, Papiamento'), array('en' => array('title' => 'Netherlands'), 'mn' => array('title' => 'Нидерланд')), 'code');
        $m->replaces(array('code' => 'NO', 'speak' => 'Norwegian, Bokmål, Nynorsk'), array('mn' => array('title' => 'Норвег'), 'en' => array('title' => 'Norway')), 'code');
        $m->replaces(array('code' => 'NP', 'speak' => 'Nepali'), array('en' => array('title' => 'Nepal'), 'mn' => array('title' => 'Непал')), 'code');
        $m->replaces(array('code' => 'NR', 'speak' => 'English, Nauruan'), array('en' => array('title' => 'Nauru'), 'mn' => array('title' => 'Науру')), 'code');
        $m->replaces(array('code' => 'NU', 'speak' => 'Niuean English'), array('mn' => array('title' => 'Niue'), 'en' => array('title' => 'Niue')), 'code');
        $m->replaces(array('code' => 'NZ', 'speak' => 'English'), array('en' => array('title' => 'New Zealand'), 'mn' => array('title' => 'Шинэ Зеланд')), 'code');
        $m->replaces(array('code' => 'OM', 'speak' => 'Arabic'), array('mn' => array('title' => 'Оман'), 'en' => array('title' => 'Oman')), 'code');
        $m->replaces(array('code' => 'PA', 'speak' => 'Spanish'), array('mn' => array('title' => 'Панама'), 'en' => array('title' => 'Panama')), 'code');
        $m->replaces(array('code' => 'PE', 'speak' => 'Spanish, Aymara, Quechua'), array('en' => array('title' => 'Peru'), 'mn' => array('title' => 'Перу')), 'code');
        $m->replaces(array('code' => 'PF', 'speak' => 'French'), array('mn' => array('title' => 'Францын Полинез'), 'en' => array('title' => 'French Polynesia')), 'code');
        $m->replaces(array('code' => 'PG', 'speak' => 'English, Tok Pisin, Hiri Motu'), array('mn' => array('title' => 'Шинэ Гвиней'), 'en' => array('title' => 'Papua New Guinea')), 'code');
        $m->replaces(array('code' => 'PH', 'speak' => 'English, Filipino'), array('mn' => array('title' => 'Филиппин'), 'en' => array('title' => 'Philippines')), 'code');
        $m->replaces(array('code' => 'PK', 'speak' => 'Urdu, English'), array('mn' => array('title' => 'Пакистан'), 'en' => array('title' => 'Pakistan')), 'code');
        $m->replaces(array('code' => 'PL', 'speak' => 'Polska'), array('mn' => array('title' => 'Польш'), 'en' => array('title' => 'Poland')), 'code');
        $m->replaces(array('code' => 'PM', 'speak' => 'French'), array('mn' => array('title' => 'St. Pierre and Miquelon'), 'en' => array('title' => 'St. Pierre and Miquelon')), 'code');
        $m->replaces(array('code' => 'PN', 'speak' => 'English'), array('mn' => array('title' => 'Pitcairn'), 'en' => array('title' => 'Pitcairn')), 'code');
        $m->replaces(array('code' => 'PR', 'speak' => 'Spanish, English Destinations'), array('en' => array('title' => 'Puerto Rico'), 'mn' => array('title' => 'Пуарте Рика')), 'code');
        $m->replaces(array('code' => 'PT', 'speak' => 'Portuguese'), array('mn' => array('title' => 'Португал'), 'en' => array('title' => 'Portugal')), 'code');
        $m->replaces(array('code' => 'PW', 'speak' => 'Palauan English'), array('en' => array('title' => 'Palau'), 'mn' => array('title' => 'Палау')), 'code');
        $m->replaces(array('code' => 'PY', 'speak' => 'Spanish, Paraguayan Guaraní'), array('en' => array('title' => 'Paraguay'), 'mn' => array('title' => 'Парагвай')), 'code');
        $m->replaces(array('code' => 'QA', 'speak' => 'Arabic'), array('mn' => array('title' => 'Катар'), 'en' => array('title' => 'Qatar')), 'code');
        $m->replaces(array('code' => 'RE', 'speak' => ''), array('en' => array('title' => 'Reunion'), 'mn' => array('title' => 'Reunion')), 'code');
        $m->replaces(array('code' => 'RO', 'speak' => '‎Romanian '), array('mn' => array('title' => 'Румын'), 'en' => array('title' => 'Romania')), 'code');
        $m->replaces(array('code' => 'RU', 'speak' => 'Русский'), array('mn' => array('title' => 'Орос'), 'en' => array('title' => 'Russian Federation')), 'code');
        $m->replaces(array('code' => 'RW', 'speak' => 'Kinyarwanda, French'), array('en' => array('title' => 'Rwanda'), 'mn' => array('title' => 'Руанда')), 'code');
        $m->replaces(array('code' => 'SA', 'speak' => 'Arabic'), array('mn' => array('title' => 'Саудын Араб'), 'en' => array('title' => 'Saudi Arabia')), 'code');
        $m->replaces(array('code' => 'SB', 'speak' => 'English'), array('mn' => array('title' => 'Соломоны арлууд'), 'en' => array('title' => 'Solomon Islands')), 'code');
        $m->replaces(array('code' => 'SC', 'speak' => 'French, English, Seselwa'), array('mn' => array('title' => 'Сэйшэль'), 'en' => array('title' => 'Seychelles')), 'code');
        $m->replaces(array('code' => 'SD', 'speak' => 'Arabic, English'), array('mn' => array('title' => 'Судан'), 'en' => array('title' => 'Sudan')), 'code');
        $m->replaces(array('code' => 'SE', 'speak' => '‎Swedish'), array('mn' => array('title' => 'Швед'), 'en' => array('title' => 'Sweden')), 'code');
        $m->replaces(array('code' => 'SG', 'speak' => 'English, Tamil, Malay, Mandarin'), array('mn' => array('title' => 'Сингапур'), 'en' => array('title' => 'Singapore')), 'code');
        $m->replaces(array('code' => 'SH', 'speak' => 'English'), array('mn' => array('title' => 'St. Helena'), 'en' => array('title' => 'St. Helena')), 'code');
        $m->replaces(array('code' => 'SI', 'speak' => '‎Slovene'), array('en' => array('title' => 'Slovenia'), 'mn' => array('title' => 'Словен')), 'code');
        $m->replaces(array('code' => 'SJ', 'speak' => 'Norwegian, Bokmål, Russian'), array('mn' => array('title' => 'Svalbard and Jan Mayen Islands'), 'en' => array('title' => 'Svalbard and Jan Mayen Islands')), 'code');
        $m->replaces(array('code' => 'SK', 'speak' => '‎Slovak'), array('en' => array('title' => 'Slovakia (Slovak Republic)'), 'mn' => array('title' => 'Словек')), 'code');
        $m->replaces(array('code' => 'SL', 'speak' => 'English'), array('en' => array('title' => 'Sierra Leone'), 'mn' => array('title' => 'Сьерра Леон')), 'code');
        $m->replaces(array('code' => 'SM', 'speak' => 'Italian'), array('mn' => array('title' => 'Сан Марино'), 'en' => array('title' => 'San Marino')), 'code');
        $m->replaces(array('code' => 'SN', 'speak' => 'French'), array('en' => array('title' => 'Senegal'), 'mn' => array('title' => 'Сенегал')), 'code');
        $m->replaces(array('code' => 'SO', 'speak' => 'Somali, Arabic'), array('mn' => array('title' => 'Сомали'), 'en' => array('title' => 'Somalia')), 'code');
        $m->replaces(array('code' => 'SR', 'speak' => 'Dutch'), array('mn' => array('title' => 'Суринам'), 'en' => array('title' => 'Suriname')), 'code');
        $m->replaces(array('code' => 'ST', 'speak' => 'Portuguese'), array('en' => array('title' => 'Sao Tome and Principe'), 'mn' => array('title' => 'Сан Томе Принсип')), 'code');
        $m->replaces(array('code' => 'SV', 'speak' => 'Spanish'), array('mn' => array('title' => 'Эль Сальвадор'), 'en' => array('title' => 'El Salvador')), 'code');
        $m->replaces(array('code' => 'SY', 'speak' => 'Arabic'), array('mn' => array('title' => 'Сири'), 'en' => array('title' => 'Syrian Arab Republic')), 'code');
        $m->replaces(array('code' => 'SZ', 'speak' => 'English, Swati'), array('mn' => array('title' => 'Swaziland'), 'en' => array('title' => 'Swaziland')), 'code');
        $m->replaces(array('code' => 'TC', 'speak' => 'English'), array('mn' => array('title' => 'Turks and Caicos Islands'), 'en' => array('title' => 'Turks and Caicos Islands')), 'code');
        $m->replaces(array('code' => 'TD', 'speak' => 'French, Arabic'), array('mn' => array('title' => 'Чад'), 'en' => array('title' => 'Chad')), 'code');
        $m->replaces(array('code' => 'TF', 'speak' => 'French'), array('mn' => array('title' => 'French Southern Territories'), 'en' => array('title' => 'French Southern Territories')), 'code');
        $m->replaces(array('code' => 'TG', 'speak' => 'French'), array('mn' => array('title' => 'Того'), 'en' => array('title' => 'Togo')), 'code');
        $m->replaces(array('code' => 'TH', 'speak' => 'Thai'), array('en' => array('title' => 'Thailand'), 'mn' => array('title' => 'Тайланд')), 'code');
        $m->replaces(array('code' => 'TJ', 'speak' => '‎Tajiks'), array('mn' => array('title' => 'Тажикстан'), 'en' => array('title' => 'Tajikistan')), 'code');
        $m->replaces(array('code' => 'TK', 'speak' => 'Tokelauan'), array('en' => array('title' => 'Tokelau'), 'mn' => array('title' => 'Tokelau')), 'code');
        $m->replaces(array('code' => 'TM', 'speak' => 'Turkmen'), array('en' => array('title' => 'Turkmenistan'), 'mn' => array('title' => 'Туркменстан')), 'code');
        $m->replaces(array('code' => 'TN', 'speak' => 'Arabic'), array('mn' => array('title' => 'Тунис'), 'en' => array('title' => 'Tunisia')), 'code');
        $m->replaces(array('code' => 'TO', 'speak' => 'English, Tongan'), array('en' => array('title' => 'Tonga'), 'mn' => array('title' => 'Тонга')), 'code');
        $m->replaces(array('code' => 'TR', 'speak' => 'Turkish'), array('mn' => array('title' => 'Турк'), 'en' => array('title' => 'Turkey')), 'code');
        $m->replaces(array('code' => 'TT', 'speak' => 'English'), array('mn' => array('title' => 'Бүгд Найрамдах Тринидад Тобаго'), 'en' => array('title' => 'Trinidad and Tobago')), 'code');
        $m->replaces(array('code' => 'TV', 'speak' => 'Tuvaluan English'), array('en' => array('title' => 'Tuvalu'), 'mn' => array('title' => 'Тувалу')), 'code');
        $m->replaces(array('code' => 'TW', 'speak' => 'Chinese: 臺灣省 or 台灣省'), array('mn' => array('title' => 'Тайван'), 'en' => array('title' => 'Taiwan, Province of China')), 'code');
        $m->replaces(array('code' => 'TZ', 'speak' => 'Swahili'), array('mn' => array('title' => 'Бүгд Найрамдах Танзани'), 'en' => array('title' => 'Tanzania, United Republic of')), 'code');
        $m->replaces(array('code' => 'UA', 'speak' => ''), array('mn' => array('title' => 'Украйн'), 'en' => array('title' => 'Ukraine')), 'code');
        $m->replaces(array('code' => 'UG', 'speak' => ''), array('mn' => array('title' => 'Уганда'), 'en' => array('title' => 'Uganda')), 'code');
        $m->replaces(array('code' => 'UM', 'speak' => ''), array('en' => array('title' => 'United States Minor Outlying Islands'), 'mn' => array('title' => 'United States Minor Outlying Islands')), 'code');
        $m->replaces(array('code' => 'US', 'speak' => 'English'), array('mn' => array('title' => 'Америкийн Нэгдсэн Улс'), 'en' => array('title' => 'United States')), 'code');
        $m->replaces(array('code' => 'UY', 'speak' => ''), array('mn' => array('title' => 'Урагвай'), 'en' => array('title' => 'Uruguay')), 'code');
        $m->replaces(array('code' => 'UZ', 'speak' => ''), array('en' => array('title' => 'Uzbekistan'), 'mn' => array('title' => 'Узбекстан')), 'code');
        $m->replaces(array('code' => 'VA', 'speak' => ''), array('mn' => array('title' => 'Ватикан'), 'en' => array('title' => 'Holy See (Vatican City State)')), 'code');
        $m->replaces(array('code' => 'VC', 'speak' => ''), array('en' => array('title' => 'Saint Vincent and the Grenadines'), 'mn' => array('title' => 'Сент Винсент Гренадин')), 'code');
        $m->replaces(array('code' => 'VE', 'speak' => ''), array('en' => array('title' => 'Venezuela'), 'mn' => array('title' => 'Венесуэл')), 'code');
        $m->replaces(array('code' => 'VG', 'speak' => ''), array('mn' => array('title' => 'Виржины арлууд (Британы)'), 'en' => array('title' => 'Virgin Islands (British)')), 'code');
        $m->replaces(array('code' => 'VI', 'speak' => ''), array('en' => array('title' => 'Virgin Islands (U.S.)'), 'mn' => array('title' => 'Виржины арлууд (АНУ)')), 'code');
        $m->replaces(array('code' => 'VN', 'speak' => ''), array('mn' => array('title' => 'Вьетнам'), 'en' => array('title' => 'Viet Nam')), 'code');
        $m->replaces(array('code' => 'VU', 'speak' => ''), array('mn' => array('title' => 'Вануату'), 'en' => array('title' => 'Vanuatu')), 'code');
        $m->replaces(array('code' => 'WF', 'speak' => ''), array('en' => array('title' => 'Wallis and Futuna Islands'), 'mn' => array('title' => 'Wallis and Futuna Islands')), 'code');
        $m->replaces(array('code' => 'WS', 'speak' => ''), array('mn' => array('title' => 'Самоа'), 'en' => array('title' => 'Samoa')), 'code');
        $m->replaces(array('code' => 'YE', 'speak' => ''), array('mn' => array('title' => 'Йемен'), 'en' => array('title' => 'Yemen')), 'code');
        $m->replaces(array('code' => 'YT', 'speak' => ''), array('mn' => array('title' => 'Mayotte'), 'en' => array('title' => 'Mayotte')), 'code');
        $m->replaces(array('code' => 'ZA', 'speak' => ''), array('mn' => array('title' => 'Өмнөд Африк'), 'en' => array('title' => 'South Africa')), 'code');
        $m->replaces(array('code' => 'ZM', 'speak' => ''), array('en' => array('title' => 'Zambia'), 'mn' => array('title' => 'Замби')), 'code');
        $m->replaces(array('code' => 'ZW', 'speak' => ''), array('mn' => array('title' => 'Зинбабве'), 'en' => array('title' => 'Zimbabwe')), 'code');
    }
    
    public static function translation_default_key($m) {
    // generated by codesaur v7 - Swift seizer| 2020-02-10 00:05:39 | ::1
    // model: Indoraptor\Models\Translation
    // table: default
        $m->replaces(array('_keyword_' => 'accordion', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Аккордеон'), 'en' => array('title' => 'Accordion')));
        $m->replaces(array('_keyword_' => 'account', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хэрэглэгч'), 'en' => array('title' => 'Account')));
        $m->replaces(array('_keyword_' => 'accounts', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хэрэглэгчид'), 'en' => array('title' => 'Accounts')));
        $m->replaces(array('_keyword_' => 'access-log', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хандалтын протокол'), 'en' => array('title' => 'Access Log')));
        $m->replaces(array('_keyword_' => 'action', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Үйлдэл'), 'en' => array('title' => 'Action')));
        $m->replaces(array('_keyword_' => 'actions', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Үйлдлүүд'), 'en' => array('title' => 'Actions')));
        $m->replaces(array('_keyword_' => 'active', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Идэвхитэй'), 'en' => array('title' => 'Active')));
        $m->replaces(array('_keyword_' => 'add', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Нэмэх'), 'en' => array('title' => 'Add')));
        $m->replaces(array('_keyword_' => 'address', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хаяг'), 'en' => array('title' => 'Address')));
        $m->replaces(array('_keyword_' => 'admins', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Удирдах хэрэглэгчид'), 'en' => array('title' => 'Admins')));
        $m->replaces(array('_keyword_' => 'all', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Бүх'), 'en' => array('title' => 'All')));
        $m->replaces(array('_keyword_' => 'all1', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Бүгд'), 'en' => array('title' => 'All')));
        $m->replaces(array('_keyword_' => 'alerts', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Мэдэгдлүүд'), 'en' => array('title' => 'Alerts')));
        $m->replaces(array('_keyword_' => 'back', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Буцах'), 'en' => array('title' => 'Back')));
        $m->replaces(array('_keyword_' => 'banner', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Баннер'), 'en' => array('title' => 'Banner')));
        $m->replaces(array('_keyword_' => 'boxed', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хайрцагласан'), 'en' => array('title' => 'Boxed')));
        $m->replaces(array('_keyword_' => 'cancel', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Болих'), 'en' => array('title' => 'Cancel')));
        $m->replaces(array('_keyword_' => 'categories', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Ангилалууд'), 'en' => array('title' => 'Categories')));
        $m->replaces(array('_keyword_' => 'category', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Ангилал'), 'en' => array('title' => 'Category')));
        $m->replaces(array('_keyword_' => 'change', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Өөрчлөх'), 'en' => array('title' => 'Change')));
        $m->replaces(array('_keyword_' => 'chat', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Харилцан яриа'), 'en' => array('title' => 'Chat')));
        $m->replaces(array('_keyword_' => 'city', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хот'), 'en' => array('title' => 'City')));
        $m->replaces(array('_keyword_' => 'clear', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Цэвэрлэх'), 'en' => array('title' => 'Clear')));
        $m->replaces(array('_keyword_' => 'close', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хаах'), 'en' => array('title' => 'Close')));
        $m->replaces(array('_keyword_' => 'closed', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Хаалттай'), 'en' => array('title' => 'Closed')));
        $m->replaces(array('_keyword_' => 'code', 'type' => 0, 'created_at' => '2018-12-25 17:14:34'), array('mn' => array('title' => 'Код'), 'en' => array('title' => 'Code')));
        $m->replaces(array('_keyword_' => 'copy', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Хуулах'), 'en' => array('title' => 'Copy')));
        $m->replaces(array('_keyword_' => 'ololt', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Ололт'), 'en' => array('title' => 'ololt')));
        $m->replaces(array('_keyword_' => 'comment', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Сэтгэгдэл'), 'en' => array('title' => 'Comment')));
        $m->replaces(array('_keyword_' => 'comments', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Сэтгэгдлүүд'), 'en' => array('title' => 'Comments')));
        $m->replaces(array('_keyword_' => 'confirm', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Батлах'), 'en' => array('title' => 'Confirm')));
        $m->replaces(array('_keyword_' => 'confirmation', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Лавлагаа'), 'en' => array('title' => 'Confirmation')));
        $m->replaces(array('_keyword_' => 'contact', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Холбоо барих'), 'en' => array('title' => 'Contact')));
        $m->replaces(array('_keyword_' => 'content', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Агуулга'), 'en' => array('title' => 'Content')));
        $m->replaces(array('_keyword_' => 'continue', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Үргэлжлүүлэх'), 'en' => array('title' => 'Continue')));
        $m->replaces(array('_keyword_' => 'countries', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Улсууд'), 'en' => array('title' => 'Countries')));
        $m->replaces(array('_keyword_' => 'country', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Улс'), 'en' => array('title' => 'Country')));
        $m->replaces(array('_keyword_' => 'created', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Үүсгэгдсэн'), 'en' => array('title' => 'Created')));
        $m->replaces(array('_keyword_' => 'customize', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Тохируулах'), 'en' => array('title' => 'Customize')));
        $m->replaces(array('_keyword_' => 'dark', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Бараан'), 'en' => array('title' => 'Dark')));
        $m->replaces(array('_keyword_' => 'dashboard', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Хянах самбар'), 'en' => array('title' => 'Dashboard')));
        $m->replaces(array('_keyword_' => 'date', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Он сар'), 'en' => array('title' => 'Date')));
        $m->replaces(array('_keyword_' => 'dear', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Эрхэм'), 'en' => array('title' => 'Dear')));
        $m->replaces(array('_keyword_' => 'default', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Өгөгдмөл'), 'en' => array('title' => 'Default')));
        $m->replaces(array('_keyword_' => 'delete', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Устгах'), 'en' => array('title' => 'Delete')));
        $m->replaces(array('_keyword_' => 'discussion', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Хэлэлцүүлэг'), 'en' => array('title' => 'Discussion')));
        $m->replaces(array('_keyword_' => 'dr', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Доктор'), 'en' => array('title' => 'Dr')));
        $m->replaces(array('_keyword_' => 'edit', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Засах'), 'en' => array('title' => 'Edit')));
        $m->replaces(array('_keyword_' => 'email', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Имэйл'), 'en' => array('title' => 'Email')));
        $m->replaces(array('_keyword_' => 'error', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Алдаа'), 'en' => array('title' => 'Error')));
        $m->replaces(array('_keyword_' => 'faq', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Түгээмэл асуулт хариултууд'), 'en' => array('title' => 'FAQ')));
        $m->replaces(array('_keyword_' => 'feedback', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Санал хүсэлт'), 'en' => array('title' => 'Feedback')));
        $m->replaces(array('_keyword_' => 'feedbacks', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Санал хүсэлтүүд'), 'en' => array('title' => 'Feedbacks')));
        $m->replaces(array('_keyword_' => 'female', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Эмэгтэй'), 'en' => array('title' => 'Female')));
        $m->replaces(array('_keyword_' => 'file', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Файл'), 'en' => array('title' => 'File')));
        $m->replaces(array('_keyword_' => 'files', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'Files'), 'mn' => array('title' => 'Файлууд')));
        $m->replaces(array('_keyword_' => 'first', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'First'), 'mn' => array('title' => 'Эхний')));
        $m->replaces(array('_keyword_' => 'firstname', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Нэр'), 'en' => array('title' => 'First Name')));
        $m->replaces(array('_keyword_' => 'fixed', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Бэхэлсэн'), 'en' => array('title' => 'Fixed')));
        $m->replaces(array('_keyword_' => 'flag', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'Flag'), 'mn' => array('title' => 'Далбаа')));
        $m->replaces(array('_keyword_' => 'fluid', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Урсамтгай'), 'en' => array('title' => 'Fluid')));
        $m->replaces(array('_keyword_' => 'footer', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'Footer'), 'mn' => array('title' => 'Хөл')));
        $m->replaces(array('_keyword_' => 'founders', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Үүсгэн байгуулагчид'), 'en' => array('title' => 'Founders')));
        $m->replaces(array('_keyword_' => 'framework', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'Framework'), 'mn' => array('title' => 'Фреймворк')));
        $m->replaces(array('_keyword_' => 'frontsite', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Үндсэн сайт'), 'en' => array('title' => 'Front Site')));
        $m->replaces(array('_keyword_' => 'fullname', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('en' => array('title' => 'Full Name'), 'mn' => array('title' => 'Бүтэн нэр')));
        $m->replaces(array('_keyword_' => 'fullscreen', 'type' => 0, 'created_at' => '2018-12-25 17:14:35'), array('mn' => array('title' => 'Дэлгэц дүүрэн'), 'en' => array('title' => 'Full Screen')));
        $m->replaces(array('_keyword_' => 'gallery', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Галлерэй'), 'en' => array('title' => 'Gallery')));
        $m->replaces(array('_keyword_' => 'general', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Ерөнхий'), 'en' => array('title' => 'General')));
        $m->replaces(array('_keyword_' => 'header', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Толгой'), 'en' => array('title' => 'Header')));
        $m->replaces(array('_keyword_' => 'help', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Тусламж'), 'en' => array('title' => 'Help')));
        $m->replaces(array('_keyword_' => 'history', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Түүх'), 'en' => array('title' => 'History')));
        $m->replaces(array('_keyword_' => 'home', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Home'), 'mn' => array('title' => 'Нүүр')));
        $m->replaces(array('_keyword_' => 'homepage', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Нүүр хуудас'), 'en' => array('title' => 'Home page')));
        $m->replaces(array('_keyword_' => 'hover', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Ховер'), 'en' => array('title' => 'Hover')));
        $m->replaces(array('_keyword_' => 'id', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'ID'), 'mn' => array('title' => 'Дугаар')));
        $m->replaces(array('_keyword_' => 'image', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Зураг'), 'en' => array('title' => 'Image')));
        $m->replaces(array('_keyword_' => 'images', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Images'), 'mn' => array('title' => 'Зурагнууд')));
        $m->replaces(array('_keyword_' => 'inactive', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Идэвхигүй'), 'en' => array('title' => 'Inactive')));
        $m->replaces(array('_keyword_' => 'key', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Key'), 'mn' => array('title' => 'Түлхүүр')));
        $m->replaces(array('_keyword_' => 'keyword', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Түлхүүр үг'), 'en' => array('title' => 'Keyword')));
        $m->replaces(array('_keyword_' => 'language', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Language'), 'mn' => array('title' => 'Хэл')));
        $m->replaces(array('_keyword_' => 'languages', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Хэлнүүд'), 'en' => array('title' => 'Languages')));
        $m->replaces(array('_keyword_' => 'last', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Сүүлийн'), 'en' => array('title' => 'Last')));
        $m->replaces(array('_keyword_' => 'lastname', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Овог'), 'en' => array('title' => 'Last name')));
        $m->replaces(array('_keyword_' => 'layout', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Бүтэц'), 'en' => array('title' => 'Layout')));
        $m->replaces(array('_keyword_' => 'left', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Зүүн'), 'en' => array('title' => 'Left')));
        $m->replaces(array('_keyword_' => 'light', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Цайвар'), 'en' => array('title' => 'Light')));
        $m->replaces(array('_keyword_' => 'links', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Links'), 'mn' => array('title' => 'Холбоосууд')));
        $m->replaces(array('_keyword_' => 'list', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Жагсаалт'), 'en' => array('title' => 'List')));
        $m->replaces(array('_keyword_' => 'loading', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Ачаалж байна'), 'en' => array('title' => 'Loading')));
        $m->replaces(array('_keyword_' => 'lock', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Lock'), 'mn' => array('title' => 'Түгжих')));
        $m->replaces(array('_keyword_' => 'log', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Протокол'), 'en' => array('title' => 'Log')));
        $m->replaces(array('_keyword_' => 'logs', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Протокол'), 'en' => array('title' => 'Logs')));
        $m->replaces(array('_keyword_' => 'login', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Login'), 'mn' => array('title' => 'Нэвтрэх')));
        $m->replaces(array('_keyword_' => 'signin', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Sign In'), 'mn' => array('title' => 'Нэвтрэх')));
        $m->replaces(array('_keyword_' => 'loginname', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Нэвтрэх нэр'), 'en' => array('title' => 'Login name')));
        $m->replaces(array('_keyword_' => 'logout', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Logout'), 'mn' => array('title' => 'Гарах')));
        $m->replaces(array('_keyword_' => 'lookup', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Лавлаж харах'), 'en' => array('title' => 'Lookup')));
        $m->replaces(array('_keyword_' => 'localization', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Нутагшуулалт'), 'en' => array('title' => 'Localization')));
        $m->replaces(array('_keyword_' => 'male', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Male'), 'mn' => array('title' => 'Эрэгтэй')));
        $m->replaces(array('_keyword_' => 'main-website', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('en' => array('title' => 'Main Website'), 'mn' => array('title' => 'Үндсэн веб хуудас')));
        $m->replaces(array('_keyword_' => 'media', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Медиа'), 'en' => array('title' => 'Media')));
        $m->replaces(array('_keyword_' => 'members', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Гишүүд'), 'en' => array('title' => 'Members')));
        $m->replaces(array('_keyword_' => 'menu', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Меню'), 'en' => array('title' => 'Menu')));
        $m->replaces(array('_keyword_' => 'message', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Зурвас'), 'en' => array('title' => 'Message')));
        $m->replaces(array('_keyword_' => 'messages', 'type' => 0, 'created_at' => '2018-12-25 17:14:36'), array('mn' => array('title' => 'Зурвасууд'), 'en' => array('title' => 'Messages')));
        $m->replaces(array('_keyword_' => 'meta', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Мета'), 'en' => array('title' => 'Meta')));
        $m->replaces(array('_keyword_' => 'misc', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('en' => array('title' => 'Misc'), 'mn' => array('title' => 'Бусад')));
        $m->replaces(array('_keyword_' => 'model', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Загвар'), 'en' => array('title' => 'Model')));
        $m->replaces(array('_keyword_' => 'modified', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Зассан'), 'en' => array('title' => 'Modified')));
        $m->replaces(array('_keyword_' => 'mr', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Ноён'), 'en' => array('title' => 'Mr')));
        $m->replaces(array('_keyword_' => 'ms', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хатагтай'), 'en' => array('title' => 'Mrs')));
        $m->replaces(array('_keyword_' => 'name', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Нэр'), 'en' => array('title' => 'Name')));
        $m->replaces(array('_keyword_' => 'navigation', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Залуур'), 'en' => array('title' => 'Navigation')));
        $m->replaces(array('_keyword_' => 'new', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Шинэ'), 'en' => array('title' => 'New')));
        $m->replaces(array('_keyword_' => 'news', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Мэдээлэл'), 'en' => array('title' => 'News')));
        $m->replaces(array('_keyword_' => 'next', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Дараах'), 'en' => array('title' => 'Next')));
        $m->replaces(array('_keyword_' => 'no', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Үгүй'), 'en' => array('title' => 'No')));
        $m->replaces(array('_keyword_' => 'notice', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Мэдэгдэл'), 'en' => array('title' => 'Notice')));
        $m->replaces(array('_keyword_' => 'notifications', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Мэдэгдлүүд'), 'en' => array('title' => 'Notifications')));
        $m->replaces(array('_keyword_' => 'ok', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Тийм'), 'en' => array('title' => 'Ok')));
        $m->replaces(array('_keyword_' => 'open', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Нээлттэй'), 'en' => array('title' => 'Open')));
        $m->replaces(array('_keyword_' => 'page', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хуудас'), 'en' => array('title' => 'Page')));
        $m->replaces(array('_keyword_' => 'pages', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хуудсууд'), 'en' => array('title' => 'Pages')));
        $m->replaces(array('_keyword_' => 'partners', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хамтрагч байгууллагууд'), 'en' => array('title' => 'Partners')));
        $m->replaces(array('_keyword_' => 'password', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Нууц үг'), 'en' => array('title' => 'Password')));
        $m->replaces(array('_keyword_' => 'payment', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Төлбөр'), 'en' => array('title' => 'Payment')));
        $m->replaces(array('_keyword_' => 'phone', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Утас'), 'en' => array('title' => 'Phone')));
        $m->replaces(array('_keyword_' => 'photo', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Зураг'), 'en' => array('title' => 'Photo')));
        $m->replaces(array('_keyword_' => 'play', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Тоглох'), 'en' => array('title' => 'Play')));
        $m->replaces(array('_keyword_' => 'player', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Тоглогч'), 'en' => array('title' => 'Player')));
        $m->replaces(array('_keyword_' => 'players', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Тоглогчид'), 'en' => array('title' => 'Players')));
        $m->replaces(array('_keyword_' => 'position', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Байршил'), 'en' => array('title' => 'Position')));
        $m->replaces(array('_keyword_' => 'prev', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Өмнөх'), 'en' => array('title' => 'Prev')));
        $m->replaces(array('_keyword_' => 'print', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хэвлэх'), 'en' => array('title' => 'Print')));
        $m->replaces(array('_keyword_' => 'processing', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('en' => array('title' => 'Processing'), 'mn' => array('title' => 'Боловcруулж байна')));
        $m->replaces(array('_keyword_' => 'prof', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Проф'), 'en' => array('title' => 'Prof')));
        $m->replaces(array('_keyword_' => 'profile', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хувийн мэдээлэл'), 'en' => array('title' => 'Profile')));
        $m->replaces(array('_keyword_' => 'rank', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Дараалал'), 'en' => array('title' => 'Rank')));
        $m->replaces(array('_keyword_' => 'read-more', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Дэлгэрэнгүй'), 'en' => array('title' => 'Read more')));
        $m->replaces(array('_keyword_' => 'reference', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Лавлах'), 'en' => array('title' => 'Reference')));
        $m->replaces(array('_keyword_' => 'register', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Бүртгэх'), 'en' => array('title' => 'Register')));
        $m->replaces(array('_keyword_' => 'registered', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Бүртгэгдсэн'), 'en' => array('title' => 'Registered')));
        $m->replaces(array('_keyword_' => 'remove', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Арилгах'), 'en' => array('title' => 'Remove')));
        $m->replaces(array('_keyword_' => 'reset', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хуучин байдалд нь оруулах'), 'en' => array('title' => 'Reset')));
        $m->replaces(array('_keyword_' => 'report', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Тайлан'), 'en' => array('title' => 'Report')));
        $m->replaces(array('_keyword_' => 'request', 'type' => 0, 'created_at' => '2018-12-25 17:14:37'), array('mn' => array('title' => 'Хүсэлт'), 'en' => array('title' => 'Request')));
        $m->replaces(array('_keyword_' => 'reviews', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хэлэлцүүлгүүд'), 'en' => array('title' => 'Reviews')));
        $m->replaces(array('_keyword_' => 'right', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Баруун'), 'en' => array('title' => 'Right')));
        $m->replaces(array('_keyword_' => 'role', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Үүрэг'), 'en' => array('title' => 'Role')));
        $m->replaces(array('_keyword_' => 'samples', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Загварууд'), 'en' => array('title' => 'Samples')));
        $m->replaces(array('_keyword_' => 'save', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хадгалах'), 'en' => array('title' => 'Save')));
        $m->replaces(array('_keyword_' => 'search', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хайх'), 'en' => array('title' => 'Search')));
        $m->replaces(array('_keyword_' => 'select', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Сонгох'), 'en' => array('title' => 'Select')));
        $m->replaces(array('_keyword_' => 'send', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Илгээх'), 'en' => array('title' => 'Send')));
        $m->replaces(array('_keyword_' => 'setting', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Тохиргоо'), 'en' => array('title' => 'Setting')));
        $m->replaces(array('_keyword_' => 'settings', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Тохиргоонууд'), 'en' => array('title' => 'Settings')));
        $m->replaces(array('_keyword_' => 'service', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Сервис'), 'en' => array('title' => 'Service')));
        $m->replaces(array('_keyword_' => 'share', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хуваалцах'), 'en' => array('title' => 'Share')));
        $m->replaces(array('_keyword_' => 'short', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Богино'), 'en' => array('title' => 'Short')));
        $m->replaces(array('_keyword_' => 'shortcuts', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Холбоосууд'), 'en' => array('title' => 'Shortcuts')));
        $m->replaces(array('_keyword_' => 'signup', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Бүртгүүлэх'), 'en' => array('title' => 'Sign Up')));
        $m->replaces(array('_keyword_' => 'sitemap', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Site map'), 'mn' => array('title' => 'Сайтын бүтэц')));
        $m->replaces(array('_keyword_' => 'size', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Size'), 'mn' => array('title' => 'Хэмжээ')));
        $m->replaces(array('_keyword_' => 'slider', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Slider'), 'mn' => array('title' => 'Слайд')));
        $m->replaces(array('_keyword_' => 'sorry', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Уучлаарай'), 'en' => array('title' => 'Sorry')));
        $m->replaces(array('_keyword_' => 'status', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Төлөв'), 'en' => array('title' => 'Status')));
        $m->replaces(array('_keyword_' => 'step', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Алхам'), 'en' => array('title' => 'Step')));
        $m->replaces(array('_keyword_' => 'submit', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Батлах'), 'en' => array('title' => 'Submit')));
        $m->replaces(array('_keyword_' => 'success', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Success'), 'mn' => array('title' => 'Амжилттай')));
        $m->replaces(array('_keyword_' => 'system', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'System'), 'mn' => array('title' => 'Систем')));
        $m->replaces(array('_keyword_' => 'table', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хүснэгт'), 'en' => array('title' => 'Table')));
        $m->replaces(array('_keyword_' => 'tag', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Шошиг'), 'en' => array('title' => 'Tag')));
        $m->replaces(array('_keyword_' => 'tags', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Шошигнууд'), 'en' => array('title' => 'Tags')));
        $m->replaces(array('_keyword_' => 'tasks', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Даалгаврууд'), 'en' => array('title' => 'Tasks')));
        $m->replaces(array('_keyword_' => 'telephone', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Утас'), 'en' => array('title' => 'Telephone')));
        $m->replaces(array('_keyword_' => 'theme', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Загвар'), 'en' => array('title' => 'Theme')));
        $m->replaces(array('_keyword_' => 'title', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Title'), 'mn' => array('title' => 'Гарчиг')));
        $m->replaces(array('_keyword_' => 'tool', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хэрэгсэл'), 'en' => array('title' => 'Tool')));
        $m->replaces(array('_keyword_' => 'tools', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хэрэгслүүд'), 'en' => array('title' => 'Tools')));
        $m->replaces(array('_keyword_' => 'town', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Тосгон'), 'en' => array('title' => 'Тown')));
        $m->replaces(array('_keyword_' => 'translation', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Орчуулга'), 'en' => array('title' => 'Translation')));
        $m->replaces(array('_keyword_' => 'type', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Төрөл'), 'en' => array('title' => 'Type')));
        $m->replaces(array('_keyword_' => 'or', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Эсвэл'), 'en' => array('title' => 'Or')));
        $m->replaces(array('_keyword_' => 'unknown', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Үл мэдэгдэх'), 'en' => array('title' => 'Unknown')));
        $m->replaces(array('_keyword_' => 'user', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('mn' => array('title' => 'Хэрэглэгч'), 'en' => array('title' => 'User')));
        $m->replaces(array('_keyword_' => 'username', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Username'), 'mn' => array('title' => 'Хэрэглэгчийн нэр')));
        $m->replaces(array('_keyword_' => 'users', 'type' => 0, 'created_at' => '2018-12-25 17:14:38'), array('en' => array('title' => 'Users'), 'mn' => array('title' => 'Хэрэглэгчид')));
        $m->replaces(array('_keyword_' => 'video', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Видео'), 'en' => array('title' => 'Video')));
        $m->replaces(array('_keyword_' => 'videos', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Видеонууд'), 'en' => array('title' => 'Videos')));
        $m->replaces(array('_keyword_' => 'view', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Харах'), 'en' => array('title' => 'View')));
        $m->replaces(array('_keyword_' => 'visibility', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Харагдац'), 'en' => array('title' => 'Visibility')));
        $m->replaces(array('_keyword_' => 'webpage', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Веб хуудас'), 'en' => array('title' => 'Webpage')));
        $m->replaces(array('_keyword_' => 'welcome', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Тавтай морил'), 'en' => array('title' => 'Welcome')));
        $m->replaces(array('_keyword_' => 'yes', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('en' => array('title' => 'Yes'), 'mn' => array('title' => 'Тийм')));
        $m->replaces(array('_keyword_' => 'avatar', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('en' => array('title' => 'Avatar'), 'mn' => array('title' => 'Хөрөг')));
        $m->replaces(array('_keyword_' => 'picture', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Зураг'), 'en' => array('title' => 'Picture')));
        $m->replaces(array('_keyword_' => 'detailed', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Дэлгэрэнгүй'), 'en' => array('title' => 'Detailed')));
        $m->replaces(array('_keyword_' => 'failure', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Амжилтгүй'), 'en' => array('title' => 'Failure')));
        $m->replaces(array('_keyword_' => 'introduction', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Танилцуулга'), 'en' => array('title' => 'Introduction')));
        $m->replaces(array('_keyword_' => 'information', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Мэдээлэл'), 'en' => array('title' => 'Information')));
        $m->replaces(array('_keyword_' => 'parent', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Эцэг'), 'en' => array('title' => 'Parent')));
        $m->replaces(array('_keyword_' => 'supplier', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Нийлүүлэгч'), 'en' => array('title' => 'Supplier')));
        $m->replaces(array('_keyword_' => 'number1', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Дугаар'), 'en' => array('title' => 'Number')));
        $m->replaces(array('_keyword_' => 'description', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Тайлбар'), 'en' => array('title' => 'Description')));
        $m->replaces(array('_keyword_' => 'building', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Барилга'), 'en' => array('title' => 'Building')));
        $m->replaces(array('_keyword_' => 'floor', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Давхар'), 'en' => array('title' => 'Floor')));
        $m->replaces(array('_keyword_' => 'logo', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Лого'), 'en' => array('title' => 'Logo')));
        $m->replaces(array('_keyword_' => 'room', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Өрөө'), 'en' => array('title' => 'Room')));
        $m->replaces(array('_keyword_' => 'full', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Бүрэн'), 'en' => array('title' => 'Full')));
        $m->replaces(array('_keyword_' => 'asset1', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('en' => array('title' => 'Asset'), 'mn' => array('title' => 'Үндсэн хөрөнгө')));
        $m->replaces(array('_keyword_' => 'specification', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Тодорхойлолт'), 'en' => array('title' => 'Specification')));
        $m->replaces(array('_keyword_' => 'quantity', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Тоо хэмжээ'), 'en' => array('title' => 'Quantity')));
        $m->replaces(array('_keyword_' => 'price', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Үнэ'), 'en' => array('title' => 'Price')));
        $m->replaces(array('_keyword_' => 'balance1', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Үлдэгдэл'), 'en' => array('title' => 'Balance')));
        $m->replaces(array('_keyword_' => 'highlights1', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Онцлох мэдээ'), 'en' => array('title' => 'Highlights')));
        $m->replaces(array('_keyword_' => 'register-now', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Яг одоо бүртгүүлэх'), 'en' => array('title' => 'Register now')));
        $m->replaces(array('_keyword_' => 'videos1', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Видео сан'), 'en' => array('title' => 'Videos')));
        $m->replaces(array('_keyword_' => 'create', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Үүсгэх'), 'en' => array('title' => 'Create')));
        $m->replaces(array('_keyword_' => 'menu-type', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Цэсний төрөл'), 'en' => array('title' => 'Menu Type')));
        $m->replaces(array('_keyword_' => 'alias', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Хоч'), 'en' => array('title' => 'Alias')));
        $m->replaces(array('_keyword_' => 'publish', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Нийтлэх'), 'en' => array('title' => 'Publish')));
        $m->replaces(array('_keyword_' => 'content-type', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Агуулгын төрөл'), 'en' => array('title' => 'Content Type')));
        $m->replaces(array('_keyword_' => 'read', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Унших'), 'en' => array('title' => 'Read')));
        $m->replaces(array('_keyword_' => 'write', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Бичих'), 'en' => array('title' => 'Write')));
        $m->replaces(array('_keyword_' => 'path', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Зам'), 'en' => array('title' => 'Path')));
        $m->replaces(array('_keyword_' => 'thumbnail', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Өнгөц зураг'), 'en' => array('title' => 'Thumbnail')));
        $m->replaces(array('_keyword_' => 'main-image', 'type' => 0, 'created_at' => '2018-12-25 17:14:39'), array('mn' => array('title' => 'Үндсэн зураг'), 'en' => array('title' => 'Main Image')));
        $m->replaces(array('_keyword_' => 'visible', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Харагдах'), 'en' => array('title' => 'Visible')));
        $m->replaces(array('_keyword_' => 'contact-us', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Холбоо барих'), 'en' => array('title' => 'Contact Us')));
        $m->replaces(array('_keyword_' => 'about-us', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Бидний тухай'), 'en' => array('title' => 'About Us')));
        $m->replaces(array('_keyword_' => 'events', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Үйл явдлууд'), 'en' => array('title' => 'Events')));
        $m->replaces(array('_keyword_' => 'announcement', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Зар мэдээ'), 'en' => array('title' => 'Announcement')));
        $m->replaces(array('_keyword_' => 'scholarship', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Тэтгэлэг'), 'en' => array('title' => 'Scholarship')));
        $m->replaces(array('_keyword_' => 'follow-us', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Биднийг дагах'), 'en' => array('title' => 'Follow Us')));
        $m->replaces(array('_keyword_' => 'please-wait', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Түр хүлээнэ үү'), 'en' => array('title' => 'Please wait')));
        $m->replaces(array('_keyword_' => 'enter-search-terms', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Хайх утгаа оруулна уу..'), 'en' => array('title' => 'Enter search terms..')));
        $m->replaces(array('_keyword_' => 'done', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Done'), 'mn' => array('title' => 'Болсон')));
        $m->replaces(array('_keyword_' => 'failed', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Амжилтгүй'), 'en' => array('title' => 'Failed')));
        $m->replaces(array('_keyword_' => 'warning', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Warning'), 'mn' => array('title' => 'Сануулга')));
        $m->replaces(array('_keyword_' => 'mail-carrier', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Шууданч'), 'en' => array('title' => 'Mail carrier')));
        $m->replaces(array('_keyword_' => 'social-network', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Social network'), 'mn' => array('title' => 'Олон нийтийн сүлжээ')));
        $m->replaces(array('_keyword_' => 'slide1', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Slide'), 'mn' => array('title' => 'Слайд')));
        $m->replaces(array('_keyword_' => 'style', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Style'), 'mn' => array('title' => 'Загвар')));
        $m->replaces(array('_keyword_' => 'version', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Version'), 'mn' => array('title' => 'Цуврал')));
        $m->replaces(array('_keyword_' => 'icon', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Icon'), 'mn' => array('title' => 'Дүрс')));
        $m->replaces(array('_keyword_' => 'to-upload', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'To Upload'), 'mn' => array('title' => 'Байршуулах')));
        $m->replaces(array('_keyword_' => 'options', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Options'), 'mn' => array('title' => 'Тохируулгууд')));
        $m->replaces(array('_keyword_' => 'option', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Option'), 'mn' => array('title' => 'Тохиргоо')));
        $m->replaces(array('_keyword_' => 'teaser1', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Оршил'), 'en' => array('title' => 'Teaser')));
        $m->replaces(array('_keyword_' => 'link', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('mn' => array('title' => 'Холбоос'), 'en' => array('title' => 'Link')));
        $m->replaces(array('_keyword_' => 'facebook', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Facebook'), 'mn' => array('title' => 'Фейсбүүк')));
        $m->replaces(array('_keyword_' => 'twitter', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Twitter'), 'mn' => array('title' => 'Твиттер')));
        $m->replaces(array('_keyword_' => 'twitter1', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Twitter'), 'mn' => array('title' => 'Жиргээ')));
        $m->replaces(array('_keyword_' => 'youtube', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'YouTube'), 'mn' => array('title' => 'Ютүүб')));
        $m->replaces(array('_keyword_' => 'width', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Width'), 'mn' => array('title' => 'Өргөн')));
        $m->replaces(array('_keyword_' => 'updated', 'type' => 0, 'created_at' => '2018-12-25 17:14:40'), array('en' => array('title' => 'Updated'), 'mn' => array('title' => 'Шинэчилсэн')));
        $m->replaces(array('_keyword_' => 'choose', 'type' => 0, 'created_at' => '2019-01-16 20:45:51'), array('mn' => array('title' => 'Сонгох'), 'en' => array('title' => 'Choose')));
        $m->replaces(array('_keyword_' => 'client', 'type' => 0, 'created_at' => '2019-07-05 17:26:32'), array('mn' => array('title' => 'Үйлчлүүлэгч '), 'en' => array('title' => 'Client')));
        $m->replaces(array('_keyword_' => 'sum1', 'type' => 0, 'created_at' => '2019-07-05 18:40:17'), array('mn' => array('title' => 'Дүн'), 'en' => array('title' => 'Sum')));
        $m->replaces(array('_keyword_' => 'receipt', 'type' => 0, 'created_at' => '2019-07-05 21:11:12'), array('mn' => array('title' => 'Баримт'), 'en' => array('title' => 'Receipt')));
        $m->replaces(array('_keyword_' => 'organization', 'type' => 0, 'created_at' => '2019-07-11 11:58:49', 'updated_at' => '2019-07-11 11:59:45'), array('mn' => array('title' => 'Байгууллага'), 'en' => array('title' => 'Organization')));
        $m->replaces(array('_keyword_' => 'properties', 'type' => 0, 'created_at' => '2019-07-11 12:54:52'), array('mn' => array('title' => 'Шинж чанарууд'), 'en' => array('title' => 'Properties')));
        $m->replaces(array('_keyword_' => 'group', 'type' => 0, 'created_at' => '2019-07-24 18:32:07'), array('mn' => array('title' => 'Бүлэг'), 'en' => array('title' => 'Group')));
        $m->replaces(array('_keyword_' => 'gerege', 'type' => 0, 'created_at' => '2019-07-24 18:32:07'), array('mn' => array('title' => 'Гэрэгэ'), 'en' => array('title' => 'Gerege')));
        $m->replaces(array('_keyword_' => 'developer', 'type' => 0, 'created_at' => '2019-07-24 18:32:07'), array('mn' => array('title' => 'Хөгжүүлэгч'), 'en' => array('title' => 'Developer')));
        $m->replaces(array('_keyword_' => 'open-hours', 'type' => 0, 'created_at' => '2020-02-07 16:58:07'), array('mn' => array('title' => 'Нээлттэй цаг'), 'en' => array('title' => 'Open hours')));
        $m->replaces(array('_keyword_' => 'current', 'type' => 0, 'created_at' => '2018-02-08 14:21:45'), array('en' => array('title' => 'Current'), 'mn' => array('title' => 'Одоогийн')));
        $m->replaces(array('_keyword_' => 'website', 'type' => 0, 'created_at' => '2020-02-08 19:56:01'), array('mn' => array('title' => 'Вэбсайт'), 'en' => array('title' => 'Website')));
        $m->replaces(array('_keyword_' => 'google', 'type' => 0, 'created_at' => '2020-02-08 19:56:01'), array('mn' => array('title' => 'Google'), 'en' => array('title' => 'Гүүгл')));
        $m->replaces(array('_keyword_' => 'route', 'type' => 0, 'created_at' => '2020-02-10 00:05:36'), array('mn' => array('title' => 'Чиглэл'), 'en' => array('title' => 'Route')));
    }

    public static function translation_dashboard_key($m) {
    // generated by codesaur v7 - Swift seizer| 2020-02-08 22:04:23 | ::1
    // model: Indoraptor\Models\Translation
    // table: dashboard
        $m->replaces(array('_keyword_' => 'accounts-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Та энэ хэсэгт системийн хэрэглэгчдийн бүртгэлийг удирдах боломжтой'), 'en' => array('title' => 'Here you can manage system accounts')));
        $m->replaces(array('_keyword_' => 'add-country', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Add Country'), 'mn' => array('title' => 'Шинэ улс')));
        $m->replaces(array('_keyword_' => 'add-new', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Шинэ бичлэг'), 'en' => array('title' => 'Add New')));
        $m->replaces(array('_keyword_' => 'add-record', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Add Record'), 'mn' => array('title' => 'Бичлэг нэмэх')));
        $m->replaces(array('_keyword_' => 'cant-complete-request', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хүсэлтийг гүйцээх боломжгүй'), 'en' => array('title' => 'Can\'t complete request')));
        $m->replaces(array('_keyword_' => 'confirm-info', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Мэдээллийг баталгаажуулна уу'), 'en' => array('title' => 'Please confirm infomations')));
        $m->replaces(array('_keyword_' => 'confirm-password', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Confirm Your Password'), 'mn' => array('title' => 'Нууц үгээ батлах')));
        $m->replaces(array('_keyword_' => 'created-by', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Үүсгэсэн хэрэглэгч'), 'en' => array('title' => 'Created by')));
        $m->replaces(array('_keyword_' => 'dark-header', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Dark Header'), 'mn' => array('title' => 'Бараан толгой')));
        $m->replaces(array('_keyword_' => 'date-created', 'type' => 0, 'created_at' => '2018-11-12 00:21:45', 'updated_at' => '2019-07-02 02:25:33'), array('mn' => array('title' => 'Үүссэн огноо'), 'en' => array('title' => 'Date created')));
        $m->replaces(array('_keyword_' => 'document-templates', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Баримт бичгийн загварууд'), 'en' => array('title' => 'Document Templates')));
        $m->replaces(array('_keyword_' => 'enter-valid-email', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Please enter a valid email address.'), 'mn' => array('title' => 'Имэйл хаягыг зөв оруулна уу.')));
        $m->replaces(array('_keyword_' => 'field-is-required', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'This field is required.'), 'mn' => array('title' => 'Талбарын утгыг оруулна уу.')));
        $m->replaces(array('_keyword_' => 'general-info', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Ерөнхий мэдээлэл'), 'en' => array('title' => 'General Info')));
        $m->replaces(array('_keyword_' => 'general-tables', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'General Tables'), 'mn' => array('title' => 'Ерөнхий хүснэгтүүд')));
        $m->replaces(array('_keyword_' => 'get-initial-code', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'үүсгэгч кодыг авах'), 'en' => array('title' => 'get initial code')));
        $m->replaces(array('_keyword_' => 'invalid-request', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Request is not valid!'), 'mn' => array('title' => 'Хүсэлт буруу байна!')));
        $m->replaces(array('_keyword_' => 'languages-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Here you can add remove system locales'), 'mn' => array('title' => 'Та энэ хэсэгт системийн хэлнүүдийг нэмж хасч өөрчлөх боломжтой')));
        $m->replaces(array('_keyword_' => 'last-login', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Сүүлд нэвтэрсэн'), 'en' => array('title' => 'Last Login')));
        $m->replaces(array('_keyword_' => 'light-header', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Цайвар толгой'), 'en' => array('title' => 'Light Header')));
        $m->replaces(array('_keyword_' => 'login-name', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Login Name'), 'mn' => array('title' => 'Нэвтрэх нэр')));
        $m->replaces(array('_keyword_' => 'my-calendar', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Миний календар'), 'en' => array('title' => 'My Calendar')));
        $m->replaces(array('_keyword_' => 'my-profile', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'My Profile'), 'mn' => array('title' => 'Миний профиль')));
        $m->replaces(array('_keyword_' => 'new-comment', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Шинэ сэтгэгдэл'), 'en' => array('title' => 'New Comment')));
        $m->replaces(array('_keyword_' => 'new-post', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Шинэ мэдээлэл'), 'en' => array('title' => 'New Post')));
        $m->replaces(array('_keyword_' => 'no-content-in-time', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Одоогоор харуулах мэдээлэл байхгүй'), 'en' => array('title' => 'There is no content to display')));
        $m->replaces(array('_keyword_' => 'record-successfully-deleted', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Record successfully deleted'), 'mn' => array('title' => 'Бичлэг амжилттай устлаа')));
        $m->replaces(array('_keyword_' => 'reference-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Reference module'), 'mn' => array('title' => 'Лавлах бүртгэлийг удирдах хэсэг')));
        $m->replaces(array('_keyword_' => 'reference-tables', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Лавлах хүснэгтүүд'), 'en' => array('title' => 'Reference Tables')));
        $m->replaces(array('_keyword_' => 'retype-password', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Re-type Password'), 'mn' => array('title' => 'Нууц үгээ дахин бичнэ')));
        $m->replaces(array('_keyword_' => 'rounded-corners', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Дугуй өнцөгтэй'), 'en' => array('title' => 'Rounded corners')));
        $m->replaces(array('_keyword_' => 'same-pass-again', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Please enter the same password again.'), 'mn' => array('title' => 'Нууц үгээ зөв дахин бичнэ үү')));
        $m->replaces(array('_keyword_' => 'select-a-country', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Улсаа сонгоорой'), 'en' => array('title' => 'Select a Country')));
        $m->replaces(array('_keyword_' => 'sidebar-menu', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хажуу баганын цэс'), 'en' => array('title' => 'Sidebar Menu')));
        $m->replaces(array('_keyword_' => 'sidebar-mode', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Sidebar Mode'), 'mn' => array('title' => 'Хажуу баганын төрөл')));
        $m->replaces(array('_keyword_' => 'sidebar-position', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хажуу баганын байрлал'), 'en' => array('title' => 'Sidebar Position')));
        $m->replaces(array('_keyword_' => 'sidebar-sub-menu', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Sidebar Submenu'), 'mn' => array('title' => 'Хажуу баганын дэд цэс')));
        $m->replaces(array('_keyword_' => 'square-corners', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Тэгш өнцөгтэй'), 'en' => array('title' => 'Square corners')));
        $m->replaces(array('_keyword_' => 'stock-medias', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Нөөц мэдээллүүд'), 'en' => array('title' => 'Stock Medias')));
        $m->replaces(array('_keyword_' => 'theme-style', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Загварын хэлбэр'), 'en' => array('title' => 'Theme Style')));
        $m->replaces(array('_keyword_' => 'top-dropdowns', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Top Dropdowns'), 'mn' => array('title' => 'Толгойн цэс')));
        $m->replaces(array('_keyword_' => 'translation-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Та энэ хэсэгт системийн бүх хэсэгт харагдах техтийн орчуулгыг удирдан зохион байгуулах боломжтой'), 'en' => array('title' => 'Here you can manage system-wide translations')));
        $m->replaces(array('_keyword_' => 'translation-settings', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Translation Settings'), 'mn' => array('title' => 'Орчуулгын тохиргоо')));
        $m->replaces(array('_keyword_' => 'type-message-here', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Мессежийг энд бичнэ үү'), 'en' => array('title' => 'Type message here')));
        $m->replaces(array('_keyword_' => 'visit-home', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Visit Home'), 'mn' => array('title' => 'Нүүр хуудас')));
        $m->replaces(array('_keyword_' => 'world-countries', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'World Countries'), 'mn' => array('title' => 'Дэлхийн улсууд')));
        $m->replaces(array('_keyword_' => 'writing', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Бичих'), 'en' => array('title' => 'Writing')));
        $m->replaces(array('_keyword_' => 'select-image', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Зураг сонгох'), 'en' => array('title' => 'Select an Image')));
        $m->replaces(array('_keyword_' => 'choose-file', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файлаа сонгоно уу!'), 'en' => array('title' => 'Choose a file!')));
        $m->replaces(array('_keyword_' => 'current-image', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Current Image'), 'mn' => array('title' => 'Одоогийн зураг')));
        $m->replaces(array('_keyword_' => 'change-image', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Зураг солих'), 'en' => array('title' => 'Change Image')));
        $m->replaces(array('_keyword_' => 'delete-file-ask', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Are you sure you want to delete this image?'), 'mn' => array('title' => 'Та энэ зургийг устгахдаа итгэлтэй байна уу?')));
        $m->replaces(array('_keyword_' => 'delete-image-ask', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Are you sure you want to delete this file?'), 'mn' => array('title' => 'Та энэ файлыг устгахдаа итгэлтэй байна уу?')));
        $m->replaces(array('_keyword_' => 'delete-file-success', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файлыг амжилттай устгалаа.'), 'en' => array('title' => 'File deleted successfully.')));
        $m->replaces(array('_keyword_' => 'delete-file-error', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файл устгах явцад алдаа гарлаа.'), 'en' => array('title' => 'Error occurred while deleting the file.')));
        $m->replaces(array('_keyword_' => 'ajax-error-please-refresh', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'АЖАКС алдаа гарлаа! Хуудсаа дахин дуудаж үзнэ үү!'), 'en' => array('title' => 'AJAX Error! Please refresh the page!')));
        $m->replaces(array('_keyword_' => 'portfolio-current-of-total', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => '{{current}} of {{total}}'), 'mn' => array('title' => 'Нийт {{total}} -н {{current}}')));
        $m->replaces(array('_keyword_' => 'new-account', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'New Account'), 'mn' => array('title' => 'Шинэ хэрэглэгч')));
        $m->replaces(array('_keyword_' => 'more-info', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Дэлгэрэнгүй мэдээлэл'), 'en' => array('title' => 'More Info')));
        $m->replaces(array('_keyword_' => 'inventory-list', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Inventory list'), 'mn' => array('title' => 'Эд хогшлыг данс бүртгэлд оруулсан жагсаалт')));
        $m->replaces(array('_keyword_' => 'inventory1', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Эд хөрөнгийн бүртгэл'), 'en' => array('title' => 'Inventory')));
        $m->replaces(array('_keyword_' => 'inventory-category-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => '<b>Бүтээгдэхүүн, үйлчилгээний нэгдсэн ангилал</b><br />Үндэсний статистикийн хорооны дарга, Улсын бүртгэлийн Ерөнхий газрын дарга, Мэдээлэл шуудан харилцаа холбоо технологийн газрын даргын хамтарсан тушаалаар батлав.<br /><i>Улаанбаатар 2011 он</i>'), 'en' => array('title' => '<b>Бүтээгдэхүүн, үйлчилгээний нэгдсэн ангилал</b><br />Үндэсний статистикийн хорооны дарга, Улсын бүртгэлийн Ерөнхий газрын дарга, Мэдээлэл шуудан харилцаа холбоо технологийн газрын даргын хамтарсан тушаалаар батлав.<br /><i>Улаанбаатар 2011 он</i>')));
        $m->replaces(array('_keyword_' => 'room-area-building', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Өрөө / Талбай / Байр'), 'en' => array('title' => 'Room / Area / Building')));
        $m->replaces(array('_keyword_' => 'inventory2', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Inventory'), 'mn' => array('title' => 'Үндсэн хөрөнгийн бүртгэл')));
        $m->replaces(array('_keyword_' => 'material-procurement', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хангамжын материал'), 'en' => array('title' => 'Material Procurement')));
        $m->replaces(array('_keyword_' => 'reg-number', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Регистрийн<br />дугаар'), 'en' => array('title' => 'Registration<br />number')));
        $m->replaces(array('_keyword_' => 'property-type', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Өмчийн хэлбэр'), 'en' => array('title' => 'Type')));
        $m->replaces(array('_keyword_' => 'current-logo', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Одоогын лого'), 'en' => array('title' => 'Current logo')));
        $m->replaces(array('_keyword_' => 'change-logo', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Change logo'), 'mn' => array('title' => 'Лого солих')));
        $m->replaces(array('_keyword_' => 'bank-account', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Банк / Данс'), 'en' => array('title' => 'Bank account')));
        $m->replaces(array('_keyword_' => 'partner1', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Харилцагч'), 'en' => array('title' => 'Partner')));
        $m->replaces(array('_keyword_' => 'owner1', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Эд хариуцагч'), 'en' => array('title' => 'Owner')));
        $m->replaces(array('_keyword_' => 'owner-type', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хариуцагчын төрөл'), 'en' => array('title' => 'Type')));
        $m->replaces(array('_keyword_' => 'main-menu', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Main menu'), 'mn' => array('title' => 'Үндсэн цэс')));
        $m->replaces(array('_keyword_' => 'rank-on-site', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'бичлэг сайт дээр харагдах дараалал.'), 'en' => array('title' => 'rank of the post.')));
        $m->replaces(array('_keyword_' => 'show-comment', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Show posted comments'), 'mn' => array('title' => 'Бичигдсэн сэтгэгдлүүдийг харуулна')));
        $m->replaces(array('_keyword_' => 'hide-comment', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Бичигдсэн сэтгэгдлүүдийг харуулахгүй'), 'en' => array('title' => 'Hide posted comments')));
        $m->replaces(array('_keyword_' => 'enable-comment', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Users can comment on this post'), 'mn' => array('title' => 'Зочид сэтгэгдэл үлдээж(бичиж) болно')));
        $m->replaces(array('_keyword_' => 'disable-comment', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Зочид сэтгэгдэл үлдээж(бичиж) болохгүй'), 'en' => array('title' => 'Users cannot comment on this post')));
        $m->replaces(array('_keyword_' => 'register-date', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Register Date'), 'mn' => array('title' => 'Бүртгэсэн огноо')));
        $m->replaces(array('_keyword_' => 'settings-general', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'general settings'), 'mn' => array('title' => 'ерөнхий тохируулгууд')));
        $m->replaces(array('_keyword_' => 'please-enter-title', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Please enter Title!'), 'mn' => array('title' => 'Гарчиг оруулна уу!')));
        $m->replaces(array('_keyword_' => 'please-select-an-action', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Үйлдлээ сонгоно уу'), 'en' => array('title' => 'Please select an action')));
        $m->replaces(array('_keyword_' => 'no-record-selected', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'No record selected'), 'mn' => array('title' => 'Бичлэг сонгогдоогүй байна')));
        $m->replaces(array('_keyword_' => 'title-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'бичлэгийн гарчиг'), 'mn' => array('title' => 'бичлэгийн гарчиг')));
        $m->replaces(array('_keyword_' => 'short-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'short version of content'), 'mn' => array('title' => 'бичлэгийн хураангуй агуулга')));
        $m->replaces(array('_keyword_' => 'full-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'бичлэгийн бүрэн эх'), 'en' => array('title' => 'full version of content')));
        $m->replaces(array('_keyword_' => 'content-image-note', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Зурагны төрөл ба мэдээллийг оруулах шаардлагатай.'), 'en' => array('title' => 'Image type and information need to be specified.')));
        $m->replaces(array('_keyword_' => 'select-files', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файлуудыг сонгох'), 'en' => array('title' => 'Select Files')));
        $m->replaces(array('_keyword_' => 'upload-files', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файлуудыг илгээх'), 'en' => array('title' => 'Upload Files')));
        $m->replaces(array('_keyword_' => 'pl-upload-failed', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Файлыг серверлүү хуулах явцад алдаа гарлаа.'), 'en' => array('title' => 'One of uploads failed. Please retry.')));
        $m->replaces(array('_keyword_' => 'image-files', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Image Files'), 'mn' => array('title' => 'Зургийн файлууд')));
        $m->replaces(array('_keyword_' => 'insert-to-content', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Агуулгад нэмэх'), 'en' => array('title' => 'Insert to content')));
        $m->replaces(array('_keyword_' => 'set-new-password-success', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Your password has been changed successfully! Thank you.'), 'mn' => array('title' => 'Нууц үгийг шинээр тохирууллаа. Шинэ нууц үгээ ашиглана уу.')));
        $m->replaces(array('_keyword_' => 'account-not-found', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хэрэглэгчийн мэдээлэл олдсонгүй!'), 'en' => array('title' => 'Account not found!')));
        $m->replaces(array('_keyword_' => 'emailer-not-set', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Email carrier not found!'), 'mn' => array('title' => 'Шууданчыг тохируулаагүй байна!')));
        $m->replaces(array('_keyword_' => 'email-template-not-set', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Email template not found!'), 'mn' => array('title' => 'Цахим захианы загварыг тодорхойлоогүй байна!')));
        $m->replaces(array('_keyword_' => 'user-notifications', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'User Notifications'), 'mn' => array('title' => 'Хэрэглэгчийн мэдэгдэл')));
        $m->replaces(array('_keyword_' => 'quick-actions', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Хялбар цэс'), 'en' => array('title' => 'Quick Actions')));
        $m->replaces(array('_keyword_' => 'invalid-response!', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Invalid response!'), 'mn' => array('title' => 'Алдаатай хариу!')));
        $m->replaces(array('_keyword_' => 'connection-error!', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Холболтын алдаа!'), 'en' => array('title' => 'Connection error!')));
        $m->replaces(array('_keyword_' => 'invalid-request-data', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Required fields failed to validate, please check your inputs & try again!'), 'mn' => array('title' => 'Мэдээлэл буруу оруулсан байна. Анхааралтай бөглөөд дахин оролдоно уу!')));
        $m->replaces(array('_keyword_' => 'generate-reports', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Generate Reports'), 'mn' => array('title' => 'Тайлан гаргах')));
        $m->replaces(array('_keyword_' => 'system-settings', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'System Settings'), 'mn' => array('title' => 'Системийн тохируулга')));
        $m->replaces(array('_keyword_' => 'u-have-some-form-errors', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'You have some form errors. Please check below.'), 'mn' => array('title' => 'Та мэдээллийг алдаатай бөглөсөн байна. Доорх талбаруудаа шалгана уу.')));
        $m->replaces(array('_keyword_' => 'ur-form-validation-is-successful!', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Your form validation is successful!'), 'mn' => array('title' => 'Та мэдээллийг амжилтай бөглөсөн байна!')));
        $m->replaces(array('_keyword_' => 'popup-modal', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Цонх нээж'), 'en' => array('title' => 'Popup Modal')));
        $m->replaces(array('_keyword_' => 'code-existing', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Code existing in'), 'mn' => array('title' => 'Код давхцаж байна')));
        $m->replaces(array('_keyword_' => 'active-record-shown', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'active record will shown on site.'), 'mn' => array('title' => 'идэвхитэй бичлэг сайт дээр харагдана.')));
        $m->replaces(array('_keyword_' => 'delete-ask-record?', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Та энэхүү бичлэгийг устгахдаа итгэлтэй байна уу?'), 'en' => array('title' => 'Are you sure to delete this record?')));
        $m->replaces(array('_keyword_' => 'edit-record', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Edit Record'), 'mn' => array('title' => 'Бичлэг засах')));
        $m->replaces(array('_keyword_' => 'empty-code', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Empty Code!'), 'mn' => array('title' => 'Код заавал өгөгдөх ёстой!')));
        $m->replaces(array('_keyword_' => 'empty-id', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Дугаар заавал өгөгдөх ёстой!'), 'en' => array('title' => 'Empty ID!')));
        $m->replaces(array('_keyword_' => 'empty-keyword', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Empty Keyword!'), 'mn' => array('title' => 'Түлхүүр үгийг заавал бичих ёстой!')));
        $m->replaces(array('_keyword_' => 'incomplete-values', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Шаардлагатай талбаруудын утгыг бүрэн оруулна уу!'), 'en' => array('title' => 'Please enter values for required fields!')));
        $m->replaces(array('_keyword_' => 'inline-table', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Inline Table'), 'mn' => array('title' => 'Хүснэгт дотор')));
        $m->replaces(array('_keyword_' => 'invalid-table-name', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Table name is not valid!'), 'mn' => array('title' => 'Хүснэгтийн нэр буруу байна!')));
        $m->replaces(array('_keyword_' => 'invalid-values', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Утга буруу байна!'), 'en' => array('title' => 'Invalid values!')));
        $m->replaces(array('_keyword_' => 'keyword-existing', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Keyword existing in'), 'mn' => array('title' => 'Түлхүүр үг давхцаж байна')));
        $m->replaces(array('_keyword_' => 'record-error-unknown', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Unknown error occurred while processing the request on the server.'), 'mn' => array('title' => 'Бичлэгийн явцад алдаа гарлаа.')));
        $m->replaces(array('_keyword_' => 'record-insert-success', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Бичлэг амжилттай нэмэгдлээ'), 'en' => array('title' => 'Record successfully added')));
        $m->replaces(array('_keyword_' => 'record-insert-error', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Error occurred while inserting record.'), 'mn' => array('title' => 'Бичлэг нэмэх явцад алдаа гарлаа.')));
        $m->replaces(array('_keyword_' => 'record-keyword-error', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Түлхүүр үг давхцах боломжгүй.'), 'en' => array('title' => 'It looks like [keyword] belongs to an existing record.')));
        $m->replaces(array('_keyword_' => 'record-update-success', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Record successfully edited'), 'mn' => array('title' => 'Бичлэг амжилттай засагдлаа')));
        $m->replaces(array('_keyword_' => 'record-update-error', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Error occurred while updating record.'), 'mn' => array('title' => 'Бичлэг засах явцад алдаа гарлаа.')));
        $m->replaces(array('_keyword_' => 'something-went-wrong', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('mn' => array('title' => 'Ямар нэгэн саатал учирлаа.'), 'en' => array('title' => 'Looks like something went wrong.')));
        $m->replaces(array('_keyword_' => 'error-oops', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'Oops...'), 'mn' => array('title' => 'Өө хөөрхий...')));
        $m->replaces(array('_keyword_' => 'error-we-working', 'type' => 0, 'created_at' => '2018-11-12 00:21:45'), array('en' => array('title' => 'We\'re working on it.'), 'mn' => array('title' => 'Алдааг удахгүй засах болно.')));
        $m->replaces(array('_keyword_' => 'view-record', 'type' => 0, 'created_at' => '2019-07-02 00:41:27'), array('mn' => array('title' => 'Бичлэг харах'), 'en' => array('title' => 'View record')));
        $m->replaces(array('_keyword_' => 'updated-by', 'type' => 0, 'created_at' => '2019-07-02 02:23:57', 'updated_at' => '2019-07-02 02:27:53'), array('mn' => array('title' => 'Өөрчилсөн хэрэглэгч'), 'en' => array('title' => 'Modified by')));
        $m->replaces(array('_keyword_' => 'date-modified', 'type' => 0, 'created_at' => '2019-07-02 02:25:20', 'updated_at' => '2019-07-02 02:27:04'), array('mn' => array('title' => 'Өөрчлөгдсөн огноо'), 'en' => array('title' => 'Date modified')));
        $m->replaces(array('_keyword_' => 'system-no-permission', 'type' => 0, 'created_at' => '2019-07-02 02:25:20', 'updated_at' => '2019-07-02 02:27:04'), array('mn' => array('title' => 'Уучлаарай, таньд энэ мэдээлэлд хандах эрх олгогдоогүй байна!'), 'en' => array('title' => 'Access Denied, You don\'t have permission to access on this resource!')));
        $m->replaces(array('_keyword_' => 'website-total-report', 'type' => 0, 'created_at' => '2019-07-02 02:25:20', 'updated_at' => '2019-07-02 02:27:04'), array('mn' => array('title' => 'Веб нийт хандалтын тайлан'), 'en' => array('title' => 'Website total access report')));
        $m->replaces(array('_keyword_' => 'website-mounthly-report', 'type' => 0, 'created_at' => '2020-02-08 18:28:37'), array('mn' => array('title' => 'Веб хандалтын сарын тайлан'), 'en' => array('title' => 'Website mounthly report')));
        $m->replaces(array('_keyword_' => 'google-analytics', 'type' => 0, 'created_at' => '2020-02-08 18:33:35'), array('mn' => array('title' => 'Гүүгл аналитик'), 'en' => array('title' => 'Google Analytics')));
        $m->replaces(array('_keyword_' => 'copy-translations-from', 'type' => 0, 'created_at' => '2020-02-08 22:02:21'), array('mn' => array('title' => 'Орчуулга хуулбарлах хэл'), 'en' => array('title' => 'Copy translations from')));
        $m->replaces(array('_keyword_' => 'pages-note', 'type' => 0, 'created_at' => '2019-06-06 18:14:18'), array('mn' => array('title' => 'Та энэхүү хэсэгт вебийн үндсэн мэдээлэл хуудсуудыг удирдах боломжтой'), 'en' => array('title' => 'Here in this area, you can manage website information & pages.')));
        $m->replaces(array('_keyword_' => 'new-page', 'type' => 0, 'created_at' => '2019-06-06 18:14:18'), array('mn' => array('title' => 'Шинэ хуудас'), 'en' => array('title' => 'New Page')));
        $m->replaces(array('_keyword_' => 'edit-page', 'type' => 0, 'created_at' => '2019-06-06 18:14:18'), array('mn' => array('title' => 'Хуудас засах'), 'en' => array('title' => 'Edit Page')));
        $m->replaces(array('_keyword_' => 'sub-pages', 'type' => 0, 'created_at' => '2019-06-06 18:14:18'), array('mn' => array('title' => 'Дэд хуудсууд'), 'en' => array('title' => 'Sub Pages')));
        $m->replaces(array('_keyword_' => 'news-note', 'type' => 0, 'created_at' => '2019-06-06 18:14:18'), array('en' => array('title' => 'Here in this area, you can manage news, events and announcements.'), 'mn' => array('title' => 'Та энэхүү хэсэгт мэдээ мэдээлэл, үйл явдал, заруудыг удирдах боломжтой')));
        $m->replaces(array('_keyword_' => 'new-news', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('en' => array('title' => 'New Information'), 'mn' => array('title' => 'Шинэ мэдээлэл')));
        $m->replaces(array('_keyword_' => 'edit-news', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('en' => array('title' => 'Edit Information'), 'mn' => array('title' => 'Мэдээллийг засах')));
        $m->replaces(array('_keyword_' => 'date-hand', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('en' => array('title' => 'Information date'), 'mn' => array('title' => 'Мэдээллийн огноо')));
        $m->replaces(array('_keyword_' => 'add-new-language', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Шинэ хэл нэмэх'), 'en' => array('title' => 'Add New Language')));
        $m->replaces(array('_keyword_' => 'enter-language-details', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Хэлний мэдээллийг оруулна уу'), 'en' => array('title' => 'Provide language details')));
        $m->replaces(array('_keyword_' => 'lang-code-existing', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Хэлний кодыг системд ашиглаж байгаа тул өөр код сонгоно уу!'), 'en' => array('title' => 'Хэлний кодыг системд ашиглаж байгаа тул өөр код сонгоно уу!')));
        $m->replaces(array('_keyword_' => 'lang-existing', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Системд хэлийг ашиглаж байгаа тул өөр хэл сонгоно уу!'), 'en' => array('title' => 'Системд хэлийг ашиглаж байгаа тул өөр хэл сонгоно уу!')));
        $m->replaces(array('_keyword_' => 'lang-name-existing', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Системд хэлний нэрийг ашиглаж байгаа тул өөр нэр ашиглана уу!'), 'en' => array('title' => 'Системд хэлний нэрийг ашиглаж байгаа тул өөр нэр ашиглана уу!')));
        $m->replaces(array('_keyword_' => 'language-added', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Системд шинэ хэл нэмлээ.'), 'en' => array('title' => 'Системд шинэ хэл нэмлээ.')));
        $m->replaces(array('_keyword_' => 'select-translation-settings', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Орчуулгын тохиргоог сонгоно уу'), 'en' => array('title' => 'Select translation settings')));
        $m->replaces(array('_keyword_' => 'translated-tables:', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('mn' => array('title' => 'Орчуулгыг хуулсан хүснэгтүүд:'), 'en' => array('title' => 'Орчуулгыг хуулсан хүснэгтүүд:')));
    }
        //$m->replaces(array('_keyword_' => 'layerslider-note', 'type' => 0, 'created_at' => '2019-06-06 18:14:19'), array('en' => array('title' => 'Та энэ хэсэгт LayerSlider - Responsive jQuery Slider плагин программыг ашиглах боломжтой.<br /><hr> Ерөнхий тохируулга хийх бол <a href="?options=global">LayerSlider Settings</a> хуудсаар орно уу.<br /><hr> Баримт бичигтэй танилцах бол <a href="?documentation=1" target="_blank">LayerSlider Documentation</a> хуудаст хандана уу.'), 'mn' => array('title' => 'Та энэ хэсэгт LayerSlider - Responsive jQuery Slider плагин программыг ашиглах боломжтой.<br /><hr> Ерөнхий тохируулга хийх бол <a href="?options=global">LayerSlider Settings</a> хуудсаар орно уу.<br /><hr> Баримт бичигтэй танилцах бол <a href="?documentation=1" target="_blank">LayerSlider Documentation</a> хуудаст хандана уу.')));
    
    public static function translation_datatable_key($m) {
    // generated by codesaur v6 Swift seizer| 2019-06-06 18:21:00 | ::1
    // model: Indoraptor\Models\Translation
    // table: datatable
        $m->replaces(array('_keyword_' => ': activate to sort column ascending', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => ': баганыг өсөхөөр эрэмблэхийн тулд идэвхжүүл'), 'en' => array('title' => ': activate to sort column ascending')));
        $m->replaces(array('_keyword_' => ': activate to sort column descending', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => ': баганыг буурахаар эрэмблэхийн тулд идэвхжүүл'), 'en' => array('title' => ': activate to sort column descending')));
        $m->replaces(array('_keyword_' => '(filtered from _MAX_ total records)', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => '(нийт _MAX_ бичлэгээс шүүв)'), 'en' => array('title' => '(filtered from _MAX_ total records)')));
        $m->replaces(array('_keyword_' => 'no data available in table', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => 'Хүснэгтэд мэдээлэл алга'), 'en' => array('title' => 'No data available in table')));
        $m->replaces(array('_keyword_' => 'no matching records found', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => 'Тохирох бичлэг олдсонгүй'), 'en' => array('title' => 'No matching records found')));
        $m->replaces(array('_keyword_' => 'no records found to show', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => 'Харуулах бичлэг байхгүй'), 'en' => array('title' => 'No records found to show')));
        $m->replaces(array('_keyword_' => 'showing _START_ to _END_ of _TOTAL_ records', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => '_TOTAL_ бичлэгээс _START_-_END_ хооронд харуулж байна'), 'en' => array('title' => 'Showing _START_ to _END_ of _TOTAL_ records')));
        $m->replaces(array('_keyword_' => 'view _MENU_ records', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => '_MENU_ бичлэг харуулах'), 'en' => array('title' => 'View _MENU_ records')));
        $m->replaces(array('_keyword_' => 'lookup-table-duplicate', 'type' => 0, 'created_at' => '2019-06-06 18:14:21'), array('mn' => array('title' => 'Лавлах хүснэгтийг ашиглаж байна!'), 'en' => array('title' => 'Lookup table already in use!')));
    }

    public static function translation_account_key($m) {
    // generated by codesaur v6 Swift seizer| 2019-06-17 18:39:52 | ::1
    // model: Indoraptor\Models\Translation
    // table: account
        $m->replaces(array('_keyword_' => 'dont-have-account-yet', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Don\'t have an account yet?'), 'mn' => array('title' => 'Хэрэглэгч болж амжаагүй байна уу?')));
        $m->replaces(array('_keyword_' => 'username-or-email', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Username or Email'), 'mn' => array('title' => 'Нэр эсвэл имейл')));
        $m->replaces(array('_keyword_' => 'enter-account-details', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Enter your account details below:'), 'mn' => array('title' => 'Нэвтрэх эрхийн мэдээлэл бөглөнө үү:')));
        $m->replaces(array('_keyword_' => 'enter-email-below', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Enter your e-mail address!'), 'mn' => array('title' => 'Бүртгэлтэй имэйл хаягаа доор бичнэ үү!')));
        $m->replaces(array('_keyword_' => 'enter-personal-details', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('mn' => array('title' => 'Та доор хэсэгт хувийн мэдээллээ оруулна уу!'), 'en' => array('title' => 'Enter your personal details below!')));
        $m->replaces(array('_keyword_' => 'enter-username', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Enter your username'), 'mn' => array('title' => 'Хэрэглэгчийн нэрээ оруулна уу')));
        $m->replaces(array('_keyword_' => 'enter-password', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Enter your password'), 'mn' => array('title' => 'Нууц үгээ оруулна уу')));
        $m->replaces(array('_keyword_' => 'enter-username-password', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Enter your username and password'), 'mn' => array('title' => 'Хэрэглэгчийн нэр ба нууц үгээ оруулна уу')));
        $m->replaces(array('_keyword_' => 'error-account-inactive', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('mn' => array('title' => 'Нэвтрэх эрх идэвхигүй байна'), 'en' => array('title' => 'User is not active')));
        $m->replaces(array('_keyword_' => 'error-incorrect-credentials', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Invalid username or password'), 'mn' => array('title' => 'Нэвтрэх нэр эсвэл нууц үг буруу байна')));
        $m->replaces(array('_keyword_' => 'error-password-empty', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please enter password'), 'mn' => array('title' => 'Нууц үг талбарыг оруулна уу')));
        $m->replaces(array('_keyword_' => 'error-username-empty', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please enter username'), 'mn' => array('title' => 'Нэвтрэх нэр талбарыг оруулна уу')));
        $m->replaces(array('_keyword_' => 'enter-email-empty', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please enter email address'), 'mn' => array('title' => 'Имейл хаягыг оруулна уу')));
        $m->replaces(array('_keyword_' => 'enter-email-valid', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please enter valid email address'), 'mn' => array('title' => 'Имейл хаягыг зөв оруулна уу')));
        $m->replaces(array('_keyword_' => 'forgot-password', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Forgot password?'), 'mn' => array('title' => 'Нууц үгээ мартсан уу?')));
        $m->replaces(array('_keyword_' => 'or-login-with', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Or login with'), 'mn' => array('title' => 'Эсвэл энүүгээр')));
        $m->replaces(array('_keyword_' => 'please-login', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Sign In To Dashboard'), 'mn' => array('title' => 'Хэрэглэгчийн эрхээр нэвтэрнэ')));
        $m->replaces(array('_keyword_' => 'remember-me', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Remember me'), 'mn' => array('title' => 'Намайг сана!')));
        $m->replaces(array('_keyword_' => 'account-did-not-exists', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'No account with that email address exists.'), 'mn' => array('title' => 'Заасан имейл хаяг бүхий бүртгэлтэй хэрэглэгч олдсонгүй.')));
        $m->replaces(array('_keyword_' => 'reset-email-sent', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'An reset e-mail has been sent.<br />Please check your email for further instructions!'), 'mn' => array('title' => 'Нууц үгийг шинэчлэх зааврыг амжилттай илгээлээ.<br />Та заасан имейл хаягаа шалгаж зааврын дагуу нууц үгээ шинэчлэнэ үү!')));
        $m->replaces(array('_keyword_' => 'forgotten-password-reset', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('mn' => array('title' => 'Нууц үг дахин тааруулах'), 'en' => array('title' => 'Forgotten password reset')));
        $m->replaces(array('_keyword_' => 'reset-link-expired', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'This link to reset your password has expired.<br/ > For your security, this link expires 20 minutes after you request it.'), 'mn' => array('title' => 'Нууц үгийг шинэчлэх холбоосын хүчинтэй хугацаа дууссан байна.<br/ > Таны мэдээллийг хамгаалах үүднээс уг холбоост 20 минутын хугацаа өгөгдсөн байсан юм.')));
        $m->replaces(array('_keyword_' => 'set-new-password', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Set new password'), 'mn' => array('title' => 'Шинээр нууц үг тааруулах')));
        $m->replaces(array('_keyword_' => 'fill-new-password', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please fill a new password!'), 'mn' => array('title' => 'Шинэ нууц үгийг оруулна уу!')));
        $m->replaces(array('_keyword_' => 'password-must-confirm', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Please re-enter the password'), 'mn' => array('title' => 'Нууц үгийг давтан бичих хэрэгтэй')));
        $m->replaces(array('_keyword_' => 'password-must-match', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Password entries must match'), 'mn' => array('title' => 'Нууц үгийг давтан бичихдээ зөв оруулах хэрэгтэй')));
        $m->replaces(array('_keyword_' => 'password-format8', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Password must be 8 characters including 1 uppercase letter, 1 lowercase letter and numeric characters'), 'mn' => array('title' => 'Нууц үг нийт 8 тэмдэгтээс бүрдэх ёстой бөгөөд, дор хаяж 1 тоо, 1 том үсэг 1, жижиг үсэг болон 1 тусгай тэмдэгт орсон байх ёстой')));
        $m->replaces(array('_keyword_' => 'to-complete-registration-check-email', 'type' => 0, 'created_at' => '2019-06-06 18:14:22'), array('en' => array('title' => 'Thank you. To complete your registration please check your email.'), 'mn' => array('title' => 'Танд баярлалаа. Бүртгэлээ баталгаажуулахын тулд имейлээ шалгана уу.')));
        $m->replaces(array('_keyword_' => 'active-account-can-login', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'only active users can login'), 'mn' => array('title' => 'зөвхөн идэвхитэй хэрэглэгч системд нэвтэрч чадна')));
        $m->replaces(array('_keyword_' => 'add-new-account', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Add new account'), 'mn' => array('title' => 'Хэрэглэгч шинээр нэмэх')));
        $m->replaces(array('_keyword_' => 'change-avatar', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Change Avatar'), 'mn' => array('title' => 'Хөрөг солих')));
        $m->replaces(array('_keyword_' => 'change-password', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Change Password'), 'mn' => array('title' => 'Нууц үг')));
        $m->replaces(array('_keyword_' => 'edit-account', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Edit account information'), 'mn' => array('title' => 'Хэрэглэгчийн мэдээлэл өөрчлөх')));
        $m->replaces(array('_keyword_' => 'new-account', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('mn' => array('title' => 'Шинэ хэрэглэгч'), 'en' => array('title' => 'New Account')));
        $m->replaces(array('_keyword_' => 'personal-info', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Personal Info'), 'mn' => array('title' => 'Хувийн мэдээлэл')));
        $m->replaces(array('_keyword_' => 'user-role', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('mn' => array('title' => 'Дүрийн тохиргоо'), 'en' => array('title' => 'User Role')));
        $m->replaces(array('_keyword_' => 'current-password', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Current Password'), 'mn' => array('title' => 'Одоогийн нууц үг')));
        $m->replaces(array('_keyword_' => 'new-password', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('mn' => array('title' => 'Шинэ нууц үг'), 'en' => array('title' => 'New Password')));
        $m->replaces(array('_keyword_' => 'account-role', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('mn' => array('title' => 'Хэрэглэгчийн дүр'), 'en' => array('title' => 'Account Role')));
        $m->replaces(array('_keyword_' => 'new-password-error', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Error new password!'), 'mn' => array('title' => 'Шинэ нууц үг буруу тохируулсан байна')));
        $m->replaces(array('_keyword_' => 'retype-new-password', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Re-type New Password'), 'mn' => array('title' => 'Шинэ нууц үгийг давтах')));
        $m->replaces(array('_keyword_' => 'account-email-exists', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'It looks like email address belongs to an existing account.'), 'mn' => array('title' => 'Имэйл хаяг өөр хэрэглэгч дээр бүртгэгдсэн байна.')));
        $m->replaces(array('_keyword_' => 'account-exists', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'It looks like information belongs to an existing account.'), 'mn' => array('title' => 'Заасан мэдээлэл бүхий хэрэглэгч аль хэдийн бүртгэгдсэн байна.')));
        $m->replaces(array('_keyword_' => 'account-request-exists', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'It looks like information belongs to an existing request.'), 'mn' => array('title' => 'Заасан мэдээлэл бүхий хүсэлт аль хэдийн бүртгэгдсэн байна.')));
        $m->replaces(array('_keyword_' => 'account-insert-success', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Account successfully added.'), 'mn' => array('title' => 'Хэрэглэгч амжилттай нэмэгдлээ.')));
        $m->replaces(array('_keyword_' => 'account-insert-error', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Error occurred while inserting account.'), 'mn' => array('title' => 'Хэрэглэгч нэмэх явцад алдаа гарлаа.')));
        $m->replaces(array('_keyword_' => 'account-update-error', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Error occurred while updating account record.'), 'mn' => array('title' => 'Хэрэглэгчийн мэдээллийг өөрчлөх явцад алдаа гарлаа.')));
        $m->replaces(array('_keyword_' => 'account-update-success', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Account record successfully updated.'), 'mn' => array('title' => 'Хэрэглэгчийн мэдээлэл амжилттай өөрчлөгдлөө.')));
        $m->replaces(array('_keyword_' => 'password-error', 'type' => 0, 'created_at' => '2018-12-03 17:12:31'), array('en' => array('title' => 'Error setting password!'), 'mn' => array('title' => 'Нууц үг буруу тохируулсан байна')));
        $m->replaces(array('_keyword_' => 'password-reset-request', 'type' => 0, 'created_at' => '2019-06-16 23:02:46', 'updated_at' => '2019-06-17 18:39:15'), array('mn' => array('title' => 'Нууц үгээ шинэчлэх хүсэлт'), 'en' => array('title' => 'Password reset request')));
        $m->replaces(array('_keyword_' => 'request-new-account', 'type' => 0, 'created_at' => '2019-06-17 18:38:30', 'updated_at' => '2019-06-17 18:38:52'), array('mn' => array('title' => 'Хэрэглэгчээр бүртгүүлэх хүсэлт'), 'en' => array('title' => 'Request a new account')));
    }

    public static function templates($m) {
    // generated by codesaur v6 Swift seizer| 2019-06-18 00:26:39 | ::1
    // model: Indoraptor\Models\Contents
    // table: templates
        $m->replaces(array('_keyword_' => 'tos', 'type' => 2, 'legend' => 1, 'status' => '1', 'created_at' => '2019-06-06 18:20:29'), array('mn' => array('title' => 'Веб систем хэрэглэх ерөнхий нөхцөлүүд', 'short' => 'Энэхүү вебсайтад нэвтэрснээр та веб системийг хэрэглэх нөхцөл болон дагах хууль журмыг зөвшөөрсөн гэж үзэх бөгөөд дотоод хуулийг дагаж мөрдөх хариуцлагыг хүлээн зөвшөөрнө.', 'full' => '<h5>1. Нөхцөл</h5>Энэхүү вебсайтад нэвтэрснээр та веб системийг хэрэглэх нөхцөл болон дагах хууль журмыг зөвшөөрсөн гэж үзэх бөгөөд дотоод хуулийг дагаж мөрдөх хариуцлагыг хүлээн зөвшөөрнө. Хэрэв эдгээр нөхцлийг зөвшөөрөөгүй тохиолдолд вебсайтыг хэрэглэх болон нэвтрэх эрхгүй юм. Тус вебсайтад агуулагдах материалууд нь худалдааны тэмдгийн хууль болон зохиогчийн эрхийн дагуу хамгаалагдсан болно.<h5>2. Лицензийг хэрэглэх</h5><ol type="a"><li>Зөвхөн түр хугацаанд арилжааны бус хувийн сонирхлоор харахын тулд вебсайтад байрлах материалын нэг хувийг урьдчилан татаж авахаар зөвшөөрөл олгогдсон. Энэ нь лицензийн олголт юм, эрхийн шилжүүлэлт биш бөгөөд энэхүү лицензд хамаарагдахгүй зүйлс:<ul><li>материалыг өөрчлөх болон хуулбарлах;</li><li>материалыг ямар нэгэн арилжааны зорилгоор ашиглах, эсвэл олон нийтэд харуулах (арлилжааны болон арилжааны бус);</li><li>вебсайтад байрлах аливаа материалыг хөрвүүлэх эсвэл утга, чиглэлийг нь өөрчлөх;</li><li>материалаас зохиогчийн эрх болон өмчлөгчийн тамга тэмдэглэгээг арилгах, устгах;</li><li>материалыг бусдад шилжүүлэх болон бусад сүлжээнд хуулбарлан тавих;</li></ul></li><li>Хэрэв эдгээр хоригийг зөрчсөн тохиолдолд лиценз цуцлагдах боломжтой. Материалыг харах эрхгүй болсон болон лиценз цуцлагдсан үед татаж авсан бүх електрон болон хэвлэсэн хэлбэрээр байгаа материалуудыг устгах ёстой.</li></ol><h5>3. Татгалзах</h5><ol type="a"><li>Bебсайтад байрлаж буй материалууд нь өөрийн байгаа хэлбэрээрээ байршсан болно. Веб систем нь хязгаарлалтгүй, битүү баталгаа эсвэл энгийн хэрэглээнд нийцэх байдлын нөхцөл, зохих зорилгын таарамж, эсвэл оюуны өмчийн халдашгүй байдал болон бусад эрхийн зөрчил зэрэг байдлуудад аливаа баталгаа гаргахгүй болно.</li></ol><h5>4. Хязгаарлалт</h5>Веб систем болон түүний нийлүүлэгч нь вебсайтад тулгарсан материалыг хэрэглэх явцад гарсан аливаа эвдрэл гэмтэлд (мэдээлэл болон хангамжын алдагдал, эсвэл ажил хэргийн саатал) веб систем болон түүний албан ёсны төлөөлөгч амаар болон бичгээр тулгарч магадгүй эвдрэлийг сануулсан ч сануулаагүй ч хариуцлага хүлээхгүй болно. Яагаад гэвэл зарим шүүх битүү баталгаанд эсвэл эвдрэл гэмтлийн хариуцлагын хязгаарлалт тавихыг зөвшөөрдөггүй, эдгээр хязгаарлалт нь магадгүй танд хэрэгжихгүй болно.<h5>5. Хэвлэлийн алдаа болон хяналт</h5>Вебсайтад байрлах материалууд нь техникийн болон хэвлэлийн эсвэл гэрэл зургийн алдаа агуулсан байж болзошгүй юм. Веб систем нь өөрийн вебсайтад байрлах материалууд нь үнэн зөв, бүрэн бүтэн, эсвэл сүүлийн үеийн байх зэрэгт баталгаа гаргахгүй болно. Веб систем нь өөрийн вебсайтад байрлах материалуудад хэдийд ч мэдэгдэл хийхгүйгээр өөрчлөлт оруулж болно. Веб систем нь материалуудыг шинэчлэхэд үүрэг хүлээхгүй болно.<h5>6. Холбоосууд</h5>Веб систем нь өөрийн вебсайтад байрлах бусад вебсайтуудын холбоосыг хянан шалгаж үзээгүй бөгөөд уг вебсайтуудад байрлах агууламжид хариуцлага хүлээхгүй болно. Холбоосууд вебсайтад байрлаж байгаа нь веб системийн баталгаатай гэсэн ойлголт биш юм. Тухайн холбоостой вебсайтуудын хэрэглэгчийн эрсдэл нь тус хэрэглэгчийн өөрийн эрсдэл болно.<h5>7. Сайтыг хэрэглэх нөхцлийн өөрчлөлт</h5>Веб систем нь өөрийн вебсайтын хэрэглэх нөхцлийг дахин авч хэлэлцэн мэдэгдэл хийхгүйгээр хэдийд ч өөрчлөх эрхтэй болно. Тус вебсайтыг хэрэглсэнээр одоогын сайт хэрэглэх нөхцлийн хувилбарыг зөвшөөрсөнд тооцогдох болно.<h5>8. Захирагдах хууль</h5>Веб систем, вебсайттай холбоотой аливаа зарга нэхэмжлэлийг хуулийн заалтын маргааныг хамаарахгүйгээр Монгол Улсын хуулийн дагуу шийдвэрлүүлнэ.'), 'en' => array('title' => 'Web Site Terms and Conditions of Use', 'short' => 'By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws.', 'full' => '<h5>1. Terms</h5>By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.<h5>2. Use License</h5><ol type="a"><li>Permission is granted to temporarily download one copy of the materials (information or software) on web site for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:<ul><li>modify or copy the materials;</li><li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li><li>attempt to decompile or reverse engineer any software contained on web site;</li><li>remove any copyright or other proprietary notations from the materials; or</li><li>transfer the materials to another person or "mirror" the materials on any other server.</li></ul></li><li>This license shall automatically terminate if you violate any of these restrictions and may be terminated by web system at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.</li></ol><h5>3. Disclaimer</h5><ol type="a"><li>The materials on web site are provided "as is". Web system makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, web system does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.</li></ol><h5>4. Limitations</h5>In no event shall web system or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on web site, even if web system or a its authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.<h5>5. Revisions and Errata</h5>The materials appearing on web site could include technical, typographical, or photographic errors. Web system does not warrant that any of the materials on its web site are accurate, complete, or current. Web system may make changes to the materials contained on its web site at any time without notice. Web system does not, however, make any commitment to update the materials.<h5>6. Links</h5>Web system has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by web system of the site. Use of any such linked web site is at the user\'s own risk.<h5>7. Site Terms of Use Modifications</h5>Web system may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.<h5>8. Governing Law</h5>Any claim relating to web site shall be governed by the laws of Mongolia without regard to its conflict of law provisions.')));
        $m->replaces(array('_keyword_' => 'pp', 'type' => 2, 'legend' => 1, 'status' => '1', 'created_at' => '2019-06-06 18:20:29'), array('mn' => array('title' => 'Хувийн мэдээлэл нууцлалын бодлого', 'short' => 'Таны хувийн нууц бол бидэнд маш чухал. Үүнтэй холбогдуулан бид хувь хүний мэдээллийг хэрхэн цуглуулдаг, хэрэглэдэг, харилцдаг зэрэг хувийн мэдээллийн хэрэглээг ойлгуулах зорилгын доор энэхүү бодлогыг боловсруулсан юм.', 'full' => 'Таны хувийн нууц бол бидэнд маш чухал. Үүнтэй холбогдуулан бид хувь хүний мэдээллийг хэрхэн цуглуулдаг, хэрэглэдэг, харилцдаг зэрэг хувийн мэдээллийн хэрэглээг ойлгуулах зорилгын доор энэхүү бодлогыг боловсруулсан юм. Нууцлалын бодлогыг доор дурьдав.<br/><ul><li>Хувийн мэдээллийг цуглуулахын өмнө эсвэл тухайн үед нь бид ямар зорилгоор уг мэдээллийг цуглуулж байгаа тухай тодорхойлж байх болно.</li><li>Бид тухайн хувийн мэдээллийг тусгайлан зөвшөөрөл аваагүй эсвэл хуулийн дагуу шаардагдаагүй үед зөвхөн тодорхойлсон зорилго буюу түүнтэй зохилдох зорилгуудын дагуу ашиглах болно.</li><li>Бид хувийн мэдээллийг зөвхөн зорилго биелэх хүртэл шаардлагын дагуу хадгалах болно.</li><li>Бид хувийн мэдээллийг хуулийн дагуу ба үнэн шударгаар тохиромжит үед нь мэдлэгтэйгээр эсвэл тусгай зөвшөөрөл авсанаар цуглуулах болно.</li><li>Зорилгын дагуу ашиглагдах хувийн мэдээллүүд тухайн зорилготой холбоотой байх бөгөөд зорилгын нийт хэрэгцээний дагуу үнэн зөв, бүрэн бүтэн ба сүүлийн үеийн байх шаардлагатай.</li><li>Бид хувийн мэдээллийг алдагдал болон хулгайлалтаас түүнчлэн хууль бус нэвтрэлт, мэдээлэл задруулалт, хуулбарлалт болон өөрчлөлт зэргээс боломжит харуул хамгаалалтаар хамгаалах болно.</li><li>Хэрэглэгчидээ хувийн мэдээллийн зохион байгуулалттай холбоотой манай бодлого болон нууцлалын талаарх дадлагажилтаар хангагдах боломжийг бүрдүүлнэ.</li></ul>Бид хувийн мэдээллийн нууцлал нь аюулгүй байдлаар хангагдаж байгаа болохыг батлан өөрсдийн ажил хэргийг эдгээр хэм хэмжээ, зарчимын дагуу эрхлэн явуулдаг юм.'), 'en' => array('title' => 'Privacy Policy', 'short' => 'Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information.', 'full' => 'Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.<br/><ul><li>Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.</li><li>We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.</li><li>We will only retain personal information as long as necessary for the fulfillment of those purposes.</li><li>We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned.</li><li>Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date.</li><li>We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.</li><li>We will make readily available to customers information about our policies and practices relating to the management of personal information.</li></ul>We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained.')));
        $m->replaces(array('_keyword_' => 'forgotten-password-reset', 'type' => 5, 'legend' => 1, 'status' => '1', 'created_at' => '2019-06-06 18:20:29', 'updated_at' => '2019-06-16 22:19:52'), array('mn' => array('title' => 'Нууг үг дахин тааруулах', 'short' => '', 'full' => '<p>Хэн нэгэн (таныг гэж найдаж байна) системийн {@email} хаяг бүхий хэрэглэгчийн нууц үгийг шинээр тааруулах хүсэлт илгээсэн байна.</p><p>Одоогоор таны хэрэглэгчийн тохиргоонд ямар нэгэн өөрчлөлт ороогүй байгаа билээ.</p><p> </p><p>Та дараах холбоосыг дарж нууц үгээ шинээр тааруулах боломжтой:</p><p>{@link}</p><p> </p><p>Хэрвээ та энэхүү хүсэлтийг илгээгүй бол, бидэнд хариу бичиж энэ тухайгаа мэдэгдэнэ үү.</p><p>Нууг үгийг солих холбоос зөвхөн 20 минутын туршид хүчинтэй байх болно.</p><p> </p><p>Хүндэтгэсэн,</p><p>Хөгжүүлэгчдийн баг.</p>'), 'en' => array('title' => 'Forgotten password reset', 'short' => '', 'full' => '<p> </p><p>Somebody (hopefully you) requested a new password for account for {@email}.</p><p>No changes have been made to your account yet.</p><p> </p><p>You can reset your password by clicking the link below:</p><p>{@link}</p><p> </p><p>If you did no request a new password, please let us know immediately by replying to this email.</p><p>This password reset is only valid for the next 20 minutes.</p><p> </p><p>Yours,</p><p>Support Team</p>')));
        $m->replaces(array('_keyword_' => 'request-new-account', 'type' => 5, 'legend' => 1, 'status' => '1', 'created_at' => '2019-06-18 00:01:45', 'updated_at' => '2019-06-18 00:26:18'), array('mn' => array('title' => 'Хэрэглэгчээр бүртгүүлэх хүсэлт', 'short' => '', 'full' => '<p>Сайн байна уу, эрхэм {@username}!</p><p> </p><p>Та манай систем дээр [{@username}] нэр, [{@email}] хаяг бүхий шинэ хэрэглэгч бүртгүүлэх хүсэлт илгээсэн байна.</p><p>Одоогоор уг хүсэлтийн дагуу шинэ хэрэглэгчийг бид системдээ албан ёсоор бүртгээгүй байгаа билээ.</p><p>Манай систем админ хүсэлтийн дагуу мэдээллийг шалгаж үзээд тохирох үйлдлийг хийх болно.</p><p>Бидний зүгээс баталгаажсан хариуг дахин илгээх хүртэл та түр хүлээнэ үү.</p><p> </p><p>Хэрвээ та энэхүү хүсэлтийг илгээгүй бол, бидэнд хариу бичиж энэ тухайгаа мэдэгдэнэ үү.</p><p> </p><p>Хүндэтгэсэн,</p><p>Хөгжүүлэгчдийн баг.</p>'), 'en' => array('title' => 'Хэрэглэгчээр бүртгүүлэх хүсэлт', 'short' => '', 'full' => '<p>Сайн байна уу, эрхэм {@username}!</p><p> </p><p>Та манай систем дээр [{@username}] нэр, [{@email}] хаяг бүхий шинэ хэрэглэгч бүртгүүлэх хүсэлт илгээсэн байна.</p><p>Одоогоор уг хүсэлтийн дагуу шинэ хэрэглэгчийг бид системдээ албан ёсоор бүртгээгүй байгаа билээ.</p><p>Манай систем админ хүсэлтийн дагуу мэдээллийг шалгаж үзээд тохирох үйлдлийг хийх болно.</p><p>Бидний зүгээс баталгаажсан хариуг дахин илгээх хүртэл та түр хүлээнэ үү.</p><p> </p><p>Хэрвээ та энэхүү хүсэлтийг илгээгүй бол, бидэнд хариу бичиж энэ тухайгаа мэдэгдэнэ үү.</p><p> </p><p>Хүндэтгэсэн,</p><p>Хөгжүүлэгчдийн баг.</p>')));
    }
}
