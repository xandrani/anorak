<?php

/**
 * @file This file contains functionality to work out whether a word should have 'an' or 'a'
 * before it. For example 'An honest man' or 'A holistic idea', or even 'A unique idea'.
 * @date 05-Jan-2016
 * @note This code was put together after reading the forum posts here:
 * https://stackoverflow.com/questions/1288291/how-can-i-correctly-prefix-a-word-with-a-and-an
 * I took all good suggestions on board to implement my solution and also added some of my
 * own ideas. I would value feedback from people to improve this solutuion even more.
 * @author Mike Youell
 * @license GNU GENERAL PUBLIC LICENSE - Version 3, 29 June 2007
 */

include_once 'is_unknown_acronym.php';

/**
 * This class works out if a specified word should have 'an' or 'a' in front of it.
 * i.e. 'An apple' or 'An honest person' or 'A hairy mammoth'.
 */
class CIndefiniteArticle
{
   /**
    * Work out which definite article ('a' or 'an') to use before a given word.
    * The calculation is made by working out the sound of the first syllable.
    * If it SOUNDS like a consonsant (e.g. the word 'unique') then we would want
    * 'a' to be put before the word, as in 'A unique experience'. Where as the word
    * Unusual has a vowel sounding start, so we would write 'An unusual experience'.
    * This also works with acronyms. 'A NASA spaceship' and 'An NSA engineer'.
    * @param[in] $Word the word we are wanting to place an 'a' or 'an' in front of.
    * @return 'a' or 'an'
    */
   public function a_or_an( $Word )
   {
      // Create a lowercase version of the word.
      $LowercaseWord = strtolower( $Word );

      // Work out whether the word literally starts with a vowel or not.
      $WordStartsWithAVowel = $this->is_starts_with_vowel( $LowercaseWord );

      // Is this an unknown acronym? (i.e. is it a capitalised word AND not in our sound dictionary)
      if ( is_unknown_acronym( $Word ) )
      {
         // If we think it's probably an acronym then we perform the calculation on the first letter
         // of the acronym. e.g. for 'NSA" we perform the more complex test on the letter 'N'.
         // This should work because single letters are in the sound dictionary. 'N' sounds like 'EN'
         // for example.
         $LowercaseWord = $LowercaseWord [ 0 ];
      }

      // If this word is an exception to the basic 'first letter vowel test'
      if ( $this->is_exception_word( $LowercaseWord, true, false ) )
      {
         // Then we invert our logic. i.e. it is a word like 'honesty' or 'unique'
         // which means 'honesty' sounds like it starts with a vowel, whereas
         // 'unique' sounds like it starts with a consonant.
         $StartOfWordSoundsLikeAVowel = ! $WordStartsWithAVowel;
      }
      else
      {
         // The basic 'first letter vowel test' will work for this word as it's not an exception
         // to the rule.
         $StartOfWordSoundsLikeAVowel = $WordStartsWithAVowel;
      }

      // If the start of the word SOUNDS like a vowel
      if ( $StartOfWordSoundsLikeAVowel )
      {
         // then return the word in front of it will be 'an'.
         return 'an';
      }

      // otherwise 'a'.
      return 'a';
   }

   /**
    * Check whether the given word starts with a vowel or not.
    * @param[in] Word This is the word we want to check.
    * @return true if the word starts with a vowel, otherwise false.
    */
   protected function is_starts_with_vowel( $Word )
   {
      // Get the first letter of the word.
      $FirstLetterOfWord = $Word[ 0 ];

      // If the first letter of the word is a vowel
      if ( in_array( $FirstLetterOfWord, array( 'a', 'e', 'i', 'o', 'u' ) ) )
      {
         // then return true...
         return true;
      }

      // otherwise false.
      return false;
   }

   /**
    * Check whether the given word is an exception to the simple 'a' or 'an'
    * rule previously mentioned. Note that if you set $BothAmericanAndBritish to true
    * you must also set $British to true (otherwise it doesn't make sense).
    * @param[in] Word This is the word we are checking, e.g. 'honesty'.
    * @param[in] British Should we use British English? true or false.
    * @param[in] American Should we use American English? true or false.
    * @return true if this word breaks the simple rule, otherwise false.
    * @note You can use this function with both American English (AE)
    * and British English (BE) simultaneously. This is for generic English used
    * on the internet, which is often a mix of both! This can get messy of course
    * because if the word is 'herb' then it isn't clear whether we should say
    * 'an harb' (AE) or say 'a herb' (BE).
    */
   private function is_exception_word( $Word, $British = true, $American = false )
   {
      // If someone is wanting to use American English AND British English but they also
      // specify NOT to use British English then flag a warning.
      if ( ! $American && ! $British )
      {
         echo "You must choose at least American or British English.\n";
         return false;
      }

      // The American English words that are exceptions to the 'first letter vowel test'.
      $AmericanEnglishExceptionsList = array
      (
         'aaa',
         'cabok',
         'euan',
         'eubank',
         'eubanks',
         'eucalypti',
         'eucalyptus',
         'eucharist',
         'euchre',
         'euchred',
         'euclea',
         'euclid',
         'eudocia',
         'eudora',
         'eudosia',
         'eudoxia',
         'eudy',
         'eugene',
         'eugenia',
         "eugenia's",
         'eugenic',
         'eugenics',
         'eugenie',
         'eugenio',
         "eugenio's",
         'eula',
         'eulich',
         'eulogies',
         'eulogize',
         'eulogized',
         'eulogy',
         "eulogy's",
         'eunice',
         'eunuch',
         'euphemia',
         'euphemism',
         'euphemisms',
         'euphemistic',
         'euphemistically',
         'euphony',
         'euphoria',
         'euphoric',
         'euphory',
         'euphrates',
         'eurailpass',
         'eurasia',
         'eurasian',
         'eureca',
         'eureka',
         'eurest',
         'euro',
         'eurobond',
         'eurobonds',
         'eurocheck',
         'eurochecks',
         'eurocom',
         'eurocommercial',
         'eurocopter',
         'eurocopters',
         'eurocrat',
         'eurocrats',
         'eurodebenture',
         'eurodebentures',
         'eurodebt',
         'eurodeposit',
         'eurodeposits',
         'eurodisney',
         'eurodollar',
         'eurodollars',
         'eurofighter',
         'eurofighters',
         'eurofima',
         'euromark',
         'euromarket',
         'euromarkets',
         'euromissile',
         'euromissiles',
         'euromobiliare',
         "euromobiliare's",
         'euromoney',
         'euronote',
         'euronotes',
         'europa',
         'europe',
         "europe's",
         'european',
         'europeans',
         "europeans'",
         'europeenne',
         'europewide',
         'europhile',
         'europhiles',
         'europium',
         'euros',
         'eurosid',
         'eurostar',
         'eurostat',
         'eurosterling',
         'eurotunnel',
         "eurotunnel's",
         'euroyen',
         'eury',
         'eusebio',
         'eustace',
         'eustachian',
         'eustacia',
         'eustice',
         'eustis',
         'euthanasia',
         "euthanasia's",
         'euthanize',
         'eutsey',
         'eutsler',
         'ewald',
         'ewalt',
         'ewan',
         'ewart',
         'ewbal',
         'ewbank',
         'ewe',
         'ewell',
         'ewen',
         'ewer',
         'ewers',
         'ewert',
         'ewes',
         'ewig',
         'ewing',
         "ewing's",
         'ewings',
         'ewoldt',
         'ewong',
         'ewton',
         'ewy',
         'f',
         "f'd",
         "f's",
         'f.',
         "f.'s",
         'fm',
         'h',
         "h's",
         'h.',
         "h.'s",
         'hbox',
         'hces',
         'heir',
         'heiress',
         'heiresses',
         'heirloom',
         'heirlooms',
         'heironimus',
         'heirs',
         'henrique',
         'herb',
         "herb's",
         'herbaceous',
         'herbal',
         'herbalist',
         "herbalist's",
         'herbalists',
         "herbalists'",
         'herbicides',
         'herbs',
         'hfdf',
         'hgh',
         'hiaa',
         'hipolito',
         //'homage',     This is wrong. There is no silent 'h' here.
         //'homages',    This is wrong. There is no silent 'h' here.
         'honest',
         'honestly',
         'honesty',
         'honor',
         "honor's",
         'honora',
         'honorable',
         'honorably',
         'honoraria',
         'honorarium',
         'honorariums',
         'honorary',
         'honore',
         'honored',
         'honoree',
         'honorees',
         'honoria',    // This is a name... so is US or British
         'honouria',   // I added this alternative spelling of the name Honoria
         'honorific',
         'honoring',
         'honors',
         'hors-d-oeuvre',
         'hour',
         "hour's",
         'hourglass',
         'hourglasses',
         'hourigan',
         'hourihan',
         'hourlong',
         'hourly',
         'hours',
         "hours'",
         'ianovski',
         'l',
         "l's",
         'l.',
         "l.'s",
         'l.s',
         'lapd',
         "lapd's",
         'lcs',
         'lp',
         'lpn',
         'ls',
         'lsd',
         'm',
         "m's",
         'm-8',
         'm-80',
         'm-code',
         'm-codes',
         'm.',
         "m.'s",
         'm.s',
         'm1',
         'm2',
         'm3',
         'm4',
         'm5',
         'mbank',
         'mcorp',
         "mcorp's",
         'mgm',
         'mh',
         'mhm',
         'mit',
         "mj's",
         'mpeg',
         'mpg',
         'mph',
         'mtel',
         'n',
         "n's",
         'n-tuple',
         'n.',
         "n.'s",
         'n.s',
         'n92762',
         'ndau',
         'ng',
         'ngema',
         'ngo',
         'ngor',
         'ngos',
         'ngueppe',
         'nkohse',
         'npr',
         "npr's",
         'npr.org',
         'nth',
         'nvhome',
         'nvhomes',
         'nvryan',
         'n_words',
         'once',
         'one',
         "one's",
         'one-up-manship',
         'one-upmanship',
         'one-way',
         'onecomm',
         'oneness',
         'ones',
         "ones'",
         'oneself',
         'onetime',
         'onex',
         "onex's",
         'oneyear',
         'ouaga',
         'ouagadougou',
         'oui',
         'ouimet',
         'ouimette',
         'r',
         "r's",
         'r.',
         "r.'s",
         'r.s',
         'rpf',
         'rpm',
         'rzasa',
         'rzepka',
         's',
         "s's",
         's.',
         "s.'s",
         'sbf',
         'sdn',
         'stds',
         'suu',
         'u',
         "u's",
         'u.',
         "u.'s",
         'u.s',
         'uarco',
         'uart',
         'uber',
         'ubiquitous',
         'ubiquity',
         'udale',
         'udall',
         'udy',
         'ueberroth',
         'ueda',
         'ueki',
         'ueno',
         'uenohara',
         'uganda',
         "uganda's",
         'ugandan',
         'ugolin',
         'ugric',
         'uinta',
         'ukase',
         'ukraine',
         "ukraine's",
         'ukrainian',
         "ukrainian's",
         'ukrainians',
         'ukulele',
         'ula',
         'ulam',
         'uland',
         'ulee',
         "ulee's",
         'uli',
         'uliaski',
         'uliassi',
         "uliassi's",
         'ulin',
         'ulitsa',
         'ullenberg',
         'ullyses',
         'ulundi',
         'ulysses',
         'uma',
         'umass',
         'umetsu',
         'unabom',
         'unabomb',
         "unabomb's",
         'unabomber',
         "unabomber's",
         'unabombers',
         'unabombing',
         'unabombings',
         'unanimity',
         'unanimous',
         'unanimously',
         'uneo',
         'unesco',
         'uni',
         'unibancorp',
         'unicef',
         'unicellular',
         'unicenter',
         'unicom',
         'unicorn',
         'unicorp',
         "unicorp's",
         'unicycle',
         'unicycles',
         'uniden',
         "uniden's",
         'unification',
         'unified',
         'unifil',
         'uniforce',
         'uniform',
         'uniformed',
         'uniformity',
         'uniformly',
         'uniforms',
         'unify',
         'unifying',
         'unigesco',
         'unikom',
         'unilab',
         'unilateral',
         'unilateralism',
         'unilaterally',
         'unilever',
         "unilever's",
         'unimate',
         'unimation',
         'unimedia',
         'union',
         "union's",
         'uniondale',
         'unionfed',
         'unionism',
         'unionist',
         'unionists',
         'unionization',
         'unionize',
         'unionized',
         'unionizing',
         'unions',
         "unions'",
         'unique',
         'uniquely',
         'uniqueness',
         'uniroyal',
         'unisex',
         'unisom',
         'unison',
         'unisons',
         'unisource',
         'unisys',
         "unisys'",
         "unisys's",
         'unit',
         "unit's",
         'unita',
         'unitaf',
         'unitarian',
         'unitary',
         'unitas',
         'unite',
         'united',
         "united's",
         'unitedbank',
         'unitek',
         'unitel',
         'unites',
         'unitholder',
         'unitholders',
         'uniting',
         'unitrin',
         "unitrin's",
         'unitrode',
         "unitrode's",
         'units',
         "units'",
         'unity',
         'univa',
         'univar',
         'univation',
         'universal',
         "universal's",
         'universality',
         'universally',
         'universe',
         'universes',
         'universities',
         "universities'",
         'university',
         "university's",
         'univisa',
         'univision',
         'unix',
         'unocal',
         "unocal's",
         'unosom',
         'unum',
         'ural',
         'urals',
         'uram',
         'uranium',
         'uranus',
         'ure',
         'urea',
         'uremia',
         'urethane',
         'urethra',
         'urey',
         'uri',
         "uri's",
         'urian',
         'uriarte',
         'urias',
         'uribe',
         'uric',
         'urich',
         'urick',
         'urie',
         'uriegas',
         'urinalysis',
         'urinary',
         'urinate',
         'urinating',
         'urine',
         'urioste',
         'urokinase',
         'urologist',
         'urologists',
         'urology',
         'uruguay',
         "uruguay's",
         'uruguayan',
         'ury',
         'usa',
         'usable',
         'usafe',
         'usage',
         'usages',
         'usaid',
         'usair',
         "usair's",
         'usairways',
         'usameribancs',
         'usbancorp',
         'use',
         'usec',
         'used',
         'useful',
         "useful's",
         'usefully',
         'usefulness',
         'useless',
         'uselman',
         'uselton',
         'usenet',
         'user',
         "user's",
         'users',
         "users'",
         'usery',
         'uses',
         'usines',
         'using',
         'usinor',
         'usoniam',
         'uss',
         'ustasha',
         'ustrust',
         'usual',
         'usually',
         'usurp',
         'usurpation',
         'usurped',
         'usurping',
         'usurps',
         'usury',
         'utah',
         "utah's",
         'utamaro',
         'utech',
         'utecht',
         'utensils',
         'uterine',
         'utero',
         'uterus',
         'uther',
         'utica',
         'utilicorp',
         'utilitarian',
         'utilities',
         "utilities'",
         'utility',
         "utility's",
         'utilization',
         'utilize',
         'utilized',
         'utilizes',
         'utilizing',
         'utopia',
         'utopian',
         'utopians',
         'utopias',
         'uva',
         'uwe',
         'uys',
         'u_l',
         'u_s_m_c',
         'x',
         "x's",
         'x-acto',
         'x-ray',
         'xray', // added non-hyphenated version
         'x-rays',
         'xrays', // added non-hyphenated version
         'x.',
         "x.'s",
         'x.ers',
         'x.s',
         'xers',
         'xscribe',
         'xtra',
         'ybanez',
         'ybarbo',
         'ybarra',
         'ydstie',
         "ydstie's",
         'yglesias',
         'ylvisaker',
         'ynez',
         'yniguez',
         'ynjiun',
         'ypsilanti',
         'yquem',
         'ysleta',
         'yttrium',
         'yves',
         'yvette',
         'yvonne',
         "yvonne's",
         'yzaguirre'
      );

      // The British English words that are exceptions to the 'first letter vowel test'.
      $BritishEnglishExceptionsList = array
      (
         'aaa',
         'cabok',
         'euan',
         'eubank',
         'eubanks',
         'eucalypti',
         'eucalyptus',
         'eucharist',
         'euchre',
         'euchred',
         'euclea',
         'euclid',
         'eudocia',
         'eudora',
         'eudosia',
         'eudoxia',
         'eudy',
         'eugene',
         'eugenia',
         "eugenia's",
         'eugenic',
         'eugenics',
         'eugenie',
         'eugenio',
         "eugenio's",
         'eula',
         'eulich',
         'eulogies',
         'eulogize',
         'eulogise', // Added ise version
         'eulogized',
         'eulogised', // Added ise version
         'eulogy',
         "eulogy's",
         'eunice',
         'eunuch',
         'euphemia',
         'euphemism',
         'euphemisms',
         'euphemistic',
         'euphemistically',
         'euphony',
         'euphoria',
         'euphoric',
         'euphory',
         'euphrates',
         'eurailpass',
         'eurasia',
         'eurasian',
         'eureca',
         'eureka',
         'eurest',
         'euro',
         'eurobond',
         'eurobonds',
         'eurocheck',
         'eurochecks',
         'eurocom',
         'eurocommercial',
         'eurocopter',
         'eurocopters',
         'eurocrat',
         'eurocrats',
         'eurodebenture',
         'eurodebentures',
         'eurodebt',
         'eurodeposit',
         'eurodeposits',
         'eurodisney',
         'eurodollar',
         'eurodollars',
         'eurofighter',
         'eurofighters',
         'eurofima',
         'euromark',
         'euromarket',
         'euromarkets',
         'euromissile',
         'euromissiles',
         'euromobiliare',
         "euromobiliare's",
         'euromoney',
         'euronote',
         'euronotes',
         'europa',
         'europe',
         "europe's",
         'european',
         'europeans',
         "europeans'",
         'europeenne',
         'europewide',
         'europhile',
         'europhiles',
         'europium',
         'euros',
         'eurosid',
         'eurostar',
         'eurostat',
         'eurosterling',
         'eurotunnel',
         "eurotunnel's",
         'euroyen',
         'eury',
         'eusebio',
         'eustace',
         'eustachian',
         'eustacia',
         'eustice',
         'eustis',
         'euthanasia',
         "euthanasia's",
         'euthanize',
         'euthanise', // Added ise version
         'eutsey',
         'eutsler',
         'ewald',
         'ewalt',
         'ewan',
         'ewart',
         'ewbal',
         'ewbank',
         'ewe',
         'ewell',
         'ewen',
         'ewer',
         'ewers',
         'ewert',
         'ewes',
         'ewig',
         'ewing',
         "ewing's",
         'ewings',
         'ewoldt',
         'ewong',
         'ewton',
         'ewy',
         'f',
         "f'd",
         "f's",
         'f.',
         "f.'s",
         'fm',
         'h',
         "h's",
         'h.',
         "h.'s",
         'hbox',
         'hces',
         'heir',
         'heiress',
         'heiresses',
         'heirloom',
         'heirlooms',
         'heironimus',
         'heirs',
         'henrique',
         //'herb',        Different in British English
         //"herb's",      Different in British English
         //'herbaceous',  Different in British English
         //'herbal',      Different in British English
         //'herbalist',   Different in British English
         //"herbalist's", Different in British English
         //'herbalists',  Different in British English
         //"herbalists'", Different in British English
         //'herbicides',  Different in British English
         //'herbs',       Different in British English
         'hfdf',
         'hgh',
         'hiaa',
         'hipolito',
         //'homage',     This is wrong. There is no silent 'h' here.
         //'homages',    This is wrong. There is no silent 'h' here.
         'honest',
         'honestly',
         'honesty',
         'honour',     // Changed to British English spelling
         "honour's",   // Changed to British English spelling
         'honora',
         'honourable', // Changed to British English spelling
         'honourably', // Changed to British English spelling
         'honoraria',
         'honorarium',
         'honorariums',
         'honourary',  // Changed to British English spelling
         'honore',
         'honoured',   // Changed to British English spelling
         'honouree',   // Changed to British English spelling
         'honourees',  // Changed to British English spelling
         'honoria',    // This is a name... so is US or British
         'honouria',   // I added this alternative spelling of the name Honoria
         'honourific', // Changed to British English spelling
         'honouring',  // Changed to British English spelling
         'honours',    // Changed to British English spelling
         'hors-d-oeuvre',
         'hour',
         "hour's",
         'hourglass',
         'hourglasses',
         'hourigan',
         'hourihan',
         'hourlong',
         'hourly',
         'hours',
         "hours'",
         'ianovski',
         'l',
         "l's",
         'l.',
         "l.'s",
         'l.s',
         'lapd',
         "lapd's",
         'lcs',
         'lp',
         'lpn',
         'ls',
         'lsd',
         'm',
         "m's",
         'm-8',
         'm-80',
         'm-code',
         'm-codes',
         'm.',
         "m.'s",
         'm.s',
         'm1',
         'm2',
         'm3',
         'm4',
         'm5',
         'mbank',
         'mcorp',
         "mcorp's",
         'mgm',
         'mh',
         'mhm',
         'mit',
         "mj's",
         'mpeg',
         'mpg',
         'mph',
         'mtel',
         'n',
         "n's",
         'n-tuple',
         'n.',
         "n.'s",
         'n.s',
         'n92762',
         'ndau',
         'ng',
         'ngema',
         'ngo',
         'ngor',
         'ngos',
         'ngueppe',
         'nkohse',
         'npr',
         "npr's",
         'npr.org',
         'nth',
         'nvhome',
         'nvhomes',
         'nvryan',
         'n_words',
         'once',
         'one',
         "one's",
         'one-up-manship',
         'one-upmanship',
         'one-way',
         'onecomm',
         'oneness',
         'ones',
         "ones'",
         'oneself',
         'onetime',
         'onex',
         "onex's",
         'oneyear',
         'ouaga',
         'ouagadougou',
         'oui',
         'ouimet',
         'ouimette',
         'r',
         "r's",
         'r.',
         "r.'s",
         'r.s',
         'rpf',
         'rpm',
         'rzasa',
         'rzepka',
         's',
         "s's",
         's.',
         "s.'s",
         'sbf',
         'sdn',
         'stds',
         'suu',
         'u',
         "u's",
         'u.',
         "u.'s",
         'u.s',
         'uarco',
         'uart',
         'uber',
         'ubiquitous',
         'ubiquity',
         'udale',
         'udall',
         'udy',
         'ueberroth',
         'ueda',
         'ueki',
         'ueno',
         'uenohara',
         'uganda',
         "uganda's",
         'ugandan',
         'ugolin',
         'ugric',
         'uinta',
         'ukase',
         'ukraine',
         "ukraine's",
         'ukrainian',
         "ukrainian's",
         'ukrainians',
         'ukulele',
         'ula',
         'ulam',
         'uland',
         'ulee',
         "ulee's",
         'uli',
         'uliaski',
         'uliassi',
         "uliassi's",
         'ulin',
         'ulitsa',
         'ullenberg',
         'ullyses',
         'ulundi',
         'ulysses',
         'uma',
         'umass',
         'umetsu',
         'unabom',
         'unabomb',
         "unabomb's",
         'unabomber',
         "unabomber's",
         'unabombers',
         'unabombing',
         'unabombings',
         'unanimity',
         'unanimous',
         'unanimously',
         'uneo',
         'unesco',
         'uni',
         'unibancorp',
         'unicef',
         'unicellular',
         'unicenter', // There is no equivalent for British English, i.e. it shouldn't be unicentre
         'unicom',
         'unicorn',
         'unicorp',
         "unicorp's",
         'unicycle',
         'unicycles',
         'uniden',
         "uniden's",
         'unification',
         'unified',
         'unifil',
         'uniforce',
         'uniform',
         'uniformed',
         'uniformity',
         'uniformly',
         'uniforms',
         'unify',
         'unifying',
         'unigesco',
         'unikom',
         'unilab',
         'unilateral',
         'unilateralism',
         'unilaterally',
         'unilever',
         "unilever's",
         'unimate',
         'unimation',
         'unimedia',
         'union',
         "union's",
         'uniondale',
         'unionfed',
         'unionism',
         'unionist',
         'unionists',
         'unionization',
         'unionisation', // Added ise version
         'unionize',
         'unionise',     // Added ise version
         'unionized',
         'unionised',    // Added ise version
         'unionizing',
         'unionising',   // Added ise version
         'unions',
         "unions'",
         'unique',
         'uniquely',
         'uniqueness',
         'uniroyal',
         'unisex',
         'unisom',
         'unison',
         'unisons',
         'unisource',
         'unisys',
         "unisys'",
         "unisys's",
         'unit',
         "unit's",
         'unita',
         'unitaf',
         'unitarian',
         'unitary',
         'unitas',
         'unite',
         'united',
         "united's",
         'unitedbank',
         'unitek',
         'unitel',
         'unites',
         'unitholder',
         'unitholders',
         'uniting',
         'unitrin',
         "unitrin's",
         'unitrode',
         "unitrode's",
         'units',
         "units'",
         'unity',
         'univa',
         'univar',
         'univation',
         'universal',
         "universal's",
         'universality',
         'universally',
         'universe',
         'universes',
         'universities',
         "universities'",
         'university',
         "university's",
         'univisa',
         'univision',
         'unix',
         'unocal',
         "unocal's",
         'unosom',
         'unum',
         'ural',
         'urals',
         'uram',
         'uranium',
         'uranus',
         'ure',
         'urea',
         'uremia',
         'urethane',
         'urethra',
         'urey',
         'uri',
         "uri's",
         'urian',
         'uriarte',
         'urias',
         'uribe',
         'uric',
         'urich',
         'urick',
         'urie',
         'uriegas',
         'urinalysis',
         'urinary',
         'urinate',
         'urinating',
         'urine',
         'urioste',
         'urokinase',
         'urologist',
         'urologists',
         'urology',
         'uruguay',
         "uruguay's",
         'uruguayan',
         'ury',
         'usa',
         'usable',
         'usafe',
         'usage',
         'usages',
         'usaid',
         'usair',
         "usair's",
         'usairways',
         'usameribancs',
         'usbancorp',
         'use',
         'usec',
         'used',
         'useful',
         "useful's",
         'usefully',
         'usefulness',
         'useless',
         'uselman',
         'uselton',
         'usenet',
         'user',
         "user's",
         'users',
         "users'",
         'usery',
         'uses',
         'usines',
         'using',
         'usinor',
         'usoniam',
         'uss',
         'ustasha',
         'ustrust',
         'usual',
         'usually',
         'usurp',
         'usurpation',
         'usurped',
         'usurping',
         'usurps',
         'usury',
         'utah',
         "utah's",
         'utamaro',
         'utech',
         'utecht',
         'utensils',
         'uterine',
         'utero',
         'uterus',
         'uther',
         'utica',
         'utilicorp',
         'utilitarian',
         'utilities',
         "utilities'",
         'utility',
         "utility's",
         'utilization',
         'utilisation', // Added ise version
         'utilize',
         'utilise',     // Added ise version
         'utilized',
         'utilised',    // Added ise version
         'utilizes',
         'utilises',    // Added ise version
         'utilizing',
         'utilising',   // Added ise version
         'utopia',
         'utopian',
         'utopians',
         'utopias',
         'uva',
         'uwe',
         'uys',
         'u_l',
         'u_s_m_c',
         'x',
         "x's",
         'x-acto',
         'x-ray',
         'xray', // added non-hyphenated version
         'x-rays',
         'xrays', // added non-hyphenated version
         'x.',
         "x.'s",
         'x.ers',
         'x.s',
         'xers',
         'xscribe',
         'xtra',
         'ybanez',
         'ybarbo',
         'ybarra',
         'ydstie',
         "ydstie's",
         'yglesias',
         'ylvisaker',
         'ynez',
         'yniguez',
         'ynjiun',
         'ypsilanti',
         'yquem',
         'ysleta',
         'yttrium',
         'yves',
         'yvette',
         'yvonne',
         "yvonne's",
         'yzaguirre'
      );

      // Work out if the word is an exception word.
      $IsBritishExceptionWord = false;
      if ( $British )
      {
         $IsBritishExceptionWord = in_array( $Word, $BritishEnglishExceptionsList );
      }
      $IsAmericanExceptionWord = false;
      if ( $American )
      {
         $IsAmericanExceptionWord = in_array( $Word, $AmericanEnglishExceptionsList );
      }

      // Return a boolean telling the caller of the function whether this is an exception word or not.
      return ( $IsBritishExceptionWord || $IsAmericanExceptionWord );
   }

}

?>