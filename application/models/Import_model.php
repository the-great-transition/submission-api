<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(600);

require 'vendor/autoload.php';
use GuzzleHttp\Client;

function slugify($text) {
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function textHTML($string) {
	$string = htmlspecialchars($string, ENT_QUOTES);
	return $string;
}

function associateSubm($input,$lang) {
	if ($lang == 'fr') {
		$reference_subm = array(
			'subm_language' => array(
				"Français" => 1,
				"Anglais" => 0,
				"Bilingue" => 2
			),
			'subm_level' => array(
				"NA" => 0,
				"Oui" => 1,
				"Non" => 2
			),
			'subm_theme' => array(
				"Une économie pour tout le monde" => 1,
				"Transformer notre rapport à la nature" => 2,
				"Lanti-impérialisme dans un monde turbulent" => 3,
				"Repenser la démocratie et le pouvoir" => 4,
				"Décoloniser les savoirs" => 5,
				"Lutter contre les oppressions" => 6
			),
			'subm_orientation' => array(
				"Stratégies" => 1,
				"Modèles" => 2
			)
		);
	} else if ($lang == 'en') {
		$reference_subm = array(
			'subm_language' => array(
				"English" => 0,
				"Français" => 1,
				"Bilingual" => 2
			),
			'subm_level' => array(
				"NA" => 0,
				"Yes" => 1,
				"No" => 2
			),
			'subm_theme' => array(
				"An Economy in the Hands of Everyone" => 1,
				"Transforming our Relationship with Nature" => 2,
				"Anti-Imperialism in a Tempest-Tossed World" => 3,
				"Rethinking Democracy and Power" => 4,
				"Decolonizing Knowledge" => 5,
				"Fighting All Oppression" => 6
			),
			'subm_orientation' => array(
				"Strategies" => 1,
				"Models" => 2
			)
		);
	};
	$array = array(
		'subm_id' => $input[0],
		'subm_time' => $input[1],
		'subm_slug' => $input[2],
		'subm_title' => $input[3],
		'subm_description' => $input[4],
		'subm_language' => $reference_subm['subm_language'][$input[5]],
		'subm_level' => $reference_subm['subm_level'][$input[6]],
		'subm_theme' => $reference_subm['subm_theme'][str_replace("’", "", $input[7])],
		'subm_orientation' => $reference_subm['subm_orientation'][$input[8]],
		'subm_type' => $input[9],
		'subm_status' => $input[10],
		'subm_forms' => $input[11],
		'user_id' => $input[12],
		'subm_meta' => $input[13]
	);
	return $array;
}

function associatePart($input) {
	$array = array(
		'part_id' => $input[0],
		'part_slug' => $input[1],
		'part_fname' => $input[2],
		'part_lname' => $input[3],
		'part_pronouns' => $input[4],
		'part_email' => $input[5],
		'part_photo' => '',
		'part_affiliation' => $input[6],
		'part_bio' => $input[7],
		'part_city' => $input[8],
		'part_country' => $input[9],
		'part_gender' => $input[10],
		'part_minority' => $input[11],
		'user_id' => $input[12],
		'subm_id' => $input[13],
		'part_meta' => $input[14]
	);
	return $array;
}

function toFieldName($id,$lang) {
	$array = array(
		'fr' => array(
			81924959 => 'type',
			81924968 => 'comm_title',
			81924969 => 'comm_description',
			81924970 => 'comm_lang',
			81924971 => 'comm_intro',
			81924972 => 'comm_theme',
			82152525 => 'comm_orientation',
			81924973 => 'comm_panelists',
			81924978 => 'comm_p1_fname',
			81924993 => 'comm_p1_lname',
			81924977 => 'comm_p1_pronoun',
			81924979 => 'comm_p1_email',
			81924981 => 'comm_p1_affiliation',
			81924982 => 'comm_p1_bio',
			81924983 => 'comm_p1_city',
			81924984 => 'comm_p1_country',
			81924987 => 'comm_p1_gender',
			81924989 => 'comm_p1_equity',
			82152650 => 'comm_p2_fname',
			82152651 => 'comm_p2_lname',
			82152652 => 'comm_p2_pronoun',
			82152653 => 'comm_p2_email',
			82152654 => 'comm_p2_affiliation',
			82152655 => 'comm_p2_bio',
			82152656 => 'comm_p2_city',
			82152657 => 'comm_p2_country',
			82152658 => 'comm_p2_gender',
			82152659 => 'comm_p2_equity',
			82152661 => 'comm_p3_fname',
			82152662 => 'comm_p3_lname',
			82152663 => 'comm_p3_pronoun',
			82152664 => 'comm_p3_email',
			82152665 => 'comm_p3_affiliation',
			82152666 => 'comm_p3_bio',
			82152667 => 'comm_p3_city',
			82152668 => 'comm_p3_country',
			82152669 => 'comm_p3_gender',
			82152670 => 'comm_p3_equity',
			82152672 => 'comm_p4_fname',
			82152673 => 'comm_p4_lname',
			82152674 => 'comm_p4_pronoun',
			82152675 => 'comm_p4_email',
			82152676 => 'comm_p4_affiliation',
			82152677 => 'comm_p4_bio',
			82152678 => 'comm_p4_city',
			82152679 => 'comm_p4_country',
			82152680 => 'comm_p4_gender',
			82152681 => 'comm_p4_equity',
			81925039 => 'panel_title',
			81925040 => 'panel_description',
			81925041 => 'panel_lang',
			81925042 => 'panel_intro',
			82152499 => 'panel_theme',
			82152530 => 'panel_orientation',
			81925044 => 'panel_comms',
			81925072 => 'panel_chair_fname',
			81925071 => 'panel_chair_lname',
			82152869 => 'panel_chair_email',
			82152683 => 'panel_c1_title',
			82152684 => 'panel_c1_description',
			82152685 => 'panel_c1_lang',
			82152687 => 'panel_c1_theme',
			82152688 => 'panel_c1_orientation',
			82152689 => 'panel_c1_panelists',
			82152691 => 'panel_c1_p1_fname',
			82152692 => 'panel_c1_p1_lname',
			82152693 => 'panel_c1_p1_pronoun',
			82152694 => 'panel_c1_p1_email',
			82152695 => 'panel_c1_p1_affiliation',
			82152696 => 'panel_c1_p1_bio',
			82152697 => 'panel_c1_p1_city',
			82152698 => 'panel_c1_p1_country',
			82152699 => 'panel_c1_p1_gender',
			82152700 => 'panel_c1_p1_equity',
			82152702 => 'panel_c1_p2_fname',
			82152703 => 'panel_c1_p2_lname',
			82152704 => 'panel_c1_p2_pronoun',
			82152705 => 'panel_c1_p2_email',
			82152706 => 'panel_c1_p2_affiliation',
			82152707 => 'panel_c1_p2_bio',
			82152708 => 'panel_c1_p2_city',
			82152709 => 'panel_c1_p2_country',
			82152710 => 'panel_c1_p2_gender',
			82152711 => 'panel_c1_p2_equity',
			82152714 => 'panel_c2_title',
			82152715 => 'panel_c2_description',
			82152716 => 'panel_c2_lang',
			82152717 => 'panel_c2_theme',
			82152718 => 'panel_c2_orientation',
			82152719 => 'panel_c2_panelists',
			82152721 => 'panel_c2_p1_fname',
			82152722 => 'panel_c2_p1_lname',
			82152723 => 'panel_c2_p1_pronoun',
			82152724 => 'panel_c2_p1_email',
			82152725 => 'panel_c2_p1_affiliation',
			82152726 => 'panel_c2_p1_bio',
			82152727 => 'panel_c2_p1_city',
			82152728 => 'panel_c2_p1_country',
			82152729 => 'panel_c2_p1_gender',
			82152730 => 'panel_c2_p1_equity',
			82152732 => 'panel_c2_p2_fname',
			82152733 => 'panel_c2_p2_lname',
			82152734 => 'panel_c2_p2_pronoun',
			82152735 => 'panel_c2_p2_email',
			82152736 => 'panel_c2_p2_affiliation',
			82152737 => 'panel_c2_p2_bio',
			82152738 => 'panel_c2_p2_city',
			82152739 => 'panel_c2_p2_country',
			82152740 => 'panel_c2_p2_gender',
			82152741 => 'panel_c2_p2_equity',
			82152744 => 'panel_c3_title',
			82152745 => 'panel_c3_description',
			82152746 => 'panel_c3_lang',
			82152747 => 'panel_c3_theme',
			82152748 => 'panel_c3_orientation',
			82152749 => 'panel_c3_panelists',
			82152751 => 'panel_c3_p1_fname',
			82152752 => 'panel_c3_p1_lname',
			82152753 => 'panel_c3_p1_pronoun',
			82152754 => 'panel_c3_p1_email',
			82152755 => 'panel_c3_p1_affiliation',
			82152756 => 'panel_c3_p1_bio',
			82152757 => 'panel_c3_p1_city',
			82152758 => 'panel_c3_p1_country',
			82152759 => 'panel_c3_p1_gender',
			82152760 => 'panel_c3_p1_equity',
			82152762 => 'panel_c3_p2_fname',
			82152763 => 'panel_c3_p2_lname',
			82152764 => 'panel_c3_p2_pronoun',
			82152765 => 'panel_c3_p2_email',
			82152766 => 'panel_c3_p2_affiliation',
			82152767 => 'panel_c3_p2_bio',
			82152768 => 'panel_c3_p2_city',
			82152769 => 'panel_c3_p2_country',
			82152770 => 'panel_c3_p2_gender',
			82152771 => 'panel_c3_p2_equity',
			82152773 => 'panel_c4_title',
			82152774 => 'panel_c4_description',
			82152775 => 'panel_c4_lang',
			82152776 => 'panel_c4_theme',
			82152777 => 'panel_c4_orientation',
			82152778 => 'panel_c4_panelists',
			82152780 => 'panel_c4_p1_fname',
			82152781 => 'panel_c4_p1_lname',
			82152782 => 'panel_c4_p1_pronoun',
			82152783 => 'panel_c4_p1_email',
			82152784 => 'panel_c4_p1_affiliation',
			82152785 => 'panel_c4_p1_bio',
			82152786 => 'panel_c4_p1_city',
			82152787 => 'panel_c4_p1_country',
			82152788 => 'panel_c4_p1_gender',
			82152789 => 'panel_c4_p1_equity',
			82152801 => 'panel_c4_p2_fname',
			82152802 => 'panel_c4_p2_lname',
			82152803 => 'panel_c4_p2_pronoun',
			82152804 => 'panel_c4_p2_email',
			82152805 => 'panel_c4_p2_affiliation',
			82152806 => 'panel_c4_p2_bio',
			82152807 => 'panel_c4_p2_city',
			82152808 => 'panel_c4_p2_country',
			82152809 => 'panel_c4_p2_gender',
			82152810 => 'panel_c4_p2_equity',
			82152813 => 'panel_c5_title',
			82152814 => 'panel_c5_description',
			82152815 => 'panel_c5_lang',
			82152816 => 'panel_c5_theme',
			82152817 => 'panel_c5_orientation',
			82152818 => 'panel_c5_panelists',
			82152820 => 'panel_c5_p1_fname',
			82152821 => 'panel_c5_p1_lname',
			82152822 => 'panel_c5_p1_pronoun',
			82152823 => 'panel_c5_p1_email',
			82152824 => 'panel_c5_p1_affiliation',
			82152825 => 'panel_c5_p1_bio',
			82152826 => 'panel_c5_p1_city',
			82152827 => 'panel_c5_p1_country',
			82152828 => 'panel_c5_p1_gender',
			82152829 => 'panel_c5_p1_equity',
			82152831 => 'panel_c5_p2_fname',
			82152832 => 'panel_c5_p2_lname',
			82152833 => 'panel_c5_p2_pronoun',
			82152834 => 'panel_c5_p2_email',
			82152835 => 'panel_c5_p2_affiliation',
			82152836 => 'panel_c5_p2_bio',
			82152837 => 'panel_c5_p2_city',
			82152838 => 'panel_c5_p2_country',
			82152839 => 'panel_c5_p2_gender',
			82152840 => 'panel_c5_p2_equity',
			82152843 => 'workshop_title',
			82152844 => 'workshop_description',
			82152845 => 'workshop_lang',
			82152846 => 'workshop_intro',
			82152847 => 'workshop_theme',
			82152848 => 'workshop_orientation',
			82152849 => 'workshop_panelists',
			82152852 => 'workshop_chair_fname',
			82152853 => 'workshop_chair_lname',
			82158016 => 'workshop_chair_email',
			82152872 => 'workshop_p1_fname',
			82152873 => 'workshop_p1_lname',
			82152874 => 'workshop_p1_pronoun',
			82152875 => 'workshop_p1_email',
			82152876 => 'workshop_p1_affiliation',
			82152877 => 'workshop_p1_bio',
			82152878 => 'workshop_p1_city',
			82152879 => 'workshop_p1_country',
			82152880 => 'workshop_p1_gender',
			82152881 => 'workshop_p1_equity',
			82152883 => 'workshop_p2_fname',
			82152884 => 'workshop_p2_lname',
			82152885 => 'workshop_p2_pronoun',
			82152886 => 'workshop_p2_email',
			82152887 => 'workshop_p2_affiliation',
			82152888 => 'workshop_p2_bio',
			82152889 => 'workshop_p2_city',
			82152890 => 'workshop_p2_country',
			82152891 => 'workshop_p2_gender',
			82152892 => 'workshop_p2_equity',
			82152900 => 'workshop_p3_fname',
			82152901 => 'workshop_p3_lname',
			82152902 => 'workshop_p3_pronoun',
			82152903 => 'workshop_p3_email',
			82152904 => 'workshop_p3_affiliation',
			82152905 => 'workshop_p3_bio',
			82152906 => 'workshop_p3_city',
			82152907 => 'workshop_p3_country',
			82152908 => 'workshop_p3_gender',
			82152909 => 'workshop_p3_equity',
			82152911 => 'workshop_p4_fname',
			82152912 => 'workshop_p4_lname',
			82152913 => 'workshop_p4_pronoun',
			82152914 => 'workshop_p4_email',
			82152915 => 'workshop_p4_affiliation',
			82152916 => 'workshop_p4_bio',
			82152917 => 'workshop_p4_city',
			82152918 => 'workshop_p4_country',
			82152919 => 'workshop_p4_gender',
			82152920 => 'workshop_p4_equity',
			82152922 => 'workshop_p5_fname',
			82152923 => 'workshop_p5_lname',
			82152924 => 'workshop_p5_pronoun',
			82152925 => 'workshop_p5_email',
			82152926 => 'workshop_p5_affiliation',
			82152927 => 'workshop_p5_bio',
			82152928 => 'workshop_p5_city',
			82152930 => 'workshop_p5_country',
			82152931 => 'workshop_p5_gender',
			82152932 => 'workshop_p5_equity',
			81925526 => 'email',
			81925525 => 'name',
			81925527 => 'info'
		), 'en' => array(
			81274174 => 'type',
			81274539 => 'comm_title',
			81274548 => 'comm_description',
			81274549 => 'comm_lang',
			81274566 => 'comm_intro',
			81274625 => 'comm_theme',
			82159805 => 'comm_orientation',
			81274799 => 'comm_panelists',
			81374463 => 'comm_p1_fname',
			81374464 => 'comm_p1_lname',
			81374560 => 'comm_p1_pronoun',
			81374466 => 'comm_p1_email',
			81374469 => 'comm_p1_affiliation',
			81374471 => 'comm_p1_bio',
			81374472 => 'comm_p1_city',
			81374473 => 'comm_p1_country',
			81374475 => 'comm_p1_gender',
			81374487 => 'comm_p1_equity',
			82150900 => 'comm_p2_fname',
			82150901 => 'comm_p2_lname',
			82150902 => 'comm_p2_pronoun',
			82150903 => 'comm_p2_email',
			82150904 => 'comm_p2_affiliation',
			82150905 => 'comm_p2_bio',
			82150906 => 'comm_p2_city',
			82150908 => 'comm_p2_country',
			82150909 => 'comm_p2_gender',
			82150910 => 'comm_p2_equity',
			82150913 => 'comm_p3_fname',
			82150914 => 'comm_p3_lname',
			82150915 => 'comm_p3_pronoun',
			82150916 => 'comm_p3_email',
			82150917 => 'comm_p3_affiliation',
			82150918 => 'comm_p3_bio',
			82150919 => 'comm_p3_city',
			82150920 => 'comm_p3_country',
			82150921 => 'comm_p3_gender',
			82150922 => 'comm_p3_equity',
			82150927 => 'comm_p4_fname',
			82150928 => 'comm_p4_lname',
			82150929 => 'comm_p4_pronoun',
			82150930 => 'comm_p4_email',
			82150931 => 'comm_p4_affiliation',
			82150932 => 'comm_p4_bio',
			82150933 => 'comm_p4_city',
			82150934 => 'comm_p4_country',
			82150935 => 'comm_p4_gender',
			82150936 => 'comm_p4_equity',
			81375813 => 'panel_title',
			81375814 => 'panel_description',
			81375815 => 'panel_lang',
			81375816 => 'panel_intro',
			82159817 => 'panel_theme',
			82159864 => 'panel_orientation',
			81375818 => 'panel_comms',
			81376363 => 'panel_chair_fname',
			81376364 => 'panel_chair_lname',
			81376365 => 'panel_chair_email',
			81375837 => 'panel_c1_title',
			81375838 => 'panel_c1_description',
			81375839 => 'panel_c1_lang',
			82159821 => 'panel_c1_theme',
			82159849 => 'panel_c1_orientation',
			81375842 => 'panel_c1_panelists',
			82151031 => 'panel_c1_p1_fname',
			82151032 => 'panel_c1_p1_lname',
			82151033 => 'panel_c1_p1_pronoun',
			82151034 => 'panel_c1_p1_email',
			82151035 => 'panel_c1_p1_affiliation',
			82151036 => 'panel_c1_p1_bio',
			82151037 => 'panel_c1_p1_city',
			82151038 => 'panel_c1_p1_country',
			82151039 => 'panel_c1_p1_gender',
			82151040 => 'panel_c1_p1_equity',
			82151117 => 'panel_c1_p2_fname',
			82151118 => 'panel_c1_p2_lname',
			82151119 => 'panel_c1_p2_pronoun',
			82151120 => 'panel_c1_p2_email',
			82151121 => 'panel_c1_p2_affiliation',
			82151122 => 'panel_c1_p2_bio',
			82151123 => 'panel_c1_p2_city',
			82151124 => 'panel_c1_p2_country',
			82151125 => 'panel_c1_p2_gender',
			82151126 => 'panel_c1_p2_equity',
			82151132 => 'panel_c2_title',
			82151133 => 'panel_c2_description',
			82151134 => 'panel_c2_lang',
			82159833 => 'panel_c2_theme',
			82159852 => 'panel_c2_orientation',
			82151136 => 'panel_c2_panelists',
			82151138 => 'panel_c2_p1_fname',
			82151139 => 'panel_c2_p1_lname',
			82151140 => 'panel_c2_p1_pronoun',
			82151141 => 'panel_c2_p1_email',
			82151142 => 'panel_c2_p1_affiliation',
			82151143 => 'panel_c2_p1_bio',
			82151144 => 'panel_c2_p1_city',
			82151145 => 'panel_c2_p1_country',
			82151146 => 'panel_c2_p1_gender',
			82151147 => 'panel_c2_p1_equity',
			82151149 => 'panel_c2_p2_fname',
			82151150 => 'panel_c2_p2_lname',
			82151151 => 'panel_c2_p2_pronoun',
			82151152 => 'panel_c2_p2_email',
			82151153 => 'panel_c2_p2_affiliation',
			82151154 => 'panel_c2_p2_bio',
			82151155 => 'panel_c2_p2_city',
			82151156 => 'panel_c2_p2_country',
			82151157 => 'panel_c2_p2_gender',
			82151158 => 'panel_c2_p2_equity',
			82151454 => 'panel_c3_title',
			82151455 => 'panel_c3_description',
			82151456 => 'panel_c3_lang',
			82159839 => 'panel_c3_theme',
			82159853 => 'panel_c3_orientation',
			82151458 => 'panel_c3_panelists',
			82151461 => 'panel_c3_p1_fname',
			82151462 => 'panel_c3_p1_lname',
			82151463 => 'panel_c3_p1_pronoun',
			82151464 => 'panel_c3_p1_email',
			82151465 => 'panel_c3_p1_affiliation',
			82151466 => 'panel_c3_p1_bio',
			82151467 => 'panel_c3_p1_city',
			82151468 => 'panel_c3_p1_country',
			82151469 => 'panel_c3_p1_gender',
			82151470 => 'panel_c3_p1_equity',
			82151472 => 'panel_c3_p2_fname',
			82151473 => 'panel_c3_p2_lname',
			82151474 => 'panel_c3_p2_pronoun',
			82151475 => 'panel_c3_p2_email',
			82151476 => 'panel_c3_p2_affiliation',
			82151477 => 'panel_c3_p2_bio',
			82151478 => 'panel_c3_p2_city',
			82151479 => 'panel_c3_p2_country',
			82151480 => 'panel_c3_p2_gender',
			82151481 => 'panel_c3_p2_equity',
			82151175 => 'panel_c4_title',
			82151176 => 'panel_c4_description',
			82151177 => 'panel_c4_lang',
			82159843 => 'panel_c4_theme',
			82159858 => 'panel_c4_orientation',
			82151179 => 'panel_c4_panelists',
			82151226 => 'panel_c4_p1_fname',
			82151227 => 'panel_c4_p1_lname',
			82151228 => 'panel_c4_p1_pronoun',
			82151229 => 'panel_c4_p1_email',
			82151230 => 'panel_c4_p1_affiliation',
			82151231 => 'panel_c4_p1_bio',
			82151232 => 'panel_c4_p1_city',
			82151233 => 'panel_c4_p1_country',
			82151234 => 'panel_c4_p1_gender',
			82151235 => 'panel_c4_p1_equity',
			82151442 => 'panel_c4_p2_fname',
			82151443 => 'panel_c4_p2_lname',
			82151444 => 'panel_c4_p2_pronoun',
			82151445 => 'panel_c4_p2_email',
			82151446 => 'panel_c4_p2_affiliation',
			82151447 => 'panel_c4_p2_bio',
			82151448 => 'panel_c4_p2_city',
			82151449 => 'panel_c4_p2_country',
			82151450 => 'panel_c4_p2_gender',
			82151451 => 'panel_c4_p2_equity',
			82151169 => 'panel_c5_title',
			82151170 => 'panel_c5_description',
			82151171 => 'panel_c5_lang',
			82159845 => 'panel_c5_theme',
			82159859 => 'panel_c5_orientation',
			82151173 => 'panel_c5_panelists',
			82151485 => 'panel_c5_p1_fname',
			82151486 => 'panel_c5_p1_lname',
			82151487 => 'panel_c5_p1_pronoun',
			82151488 => 'panel_c5_p1_email',
			82151489 => 'panel_c5_p1_affiliation',
			82151490 => 'panel_c5_p1_bio',
			82151491 => 'panel_c5_p1_city',
			82151492 => 'panel_c5_p1_country',
			82151493 => 'panel_c5_p1_gender',
			82151494 => 'panel_c5_p1_equity',
			82151496 => 'panel_c5_p2_fname',
			82151497 => 'panel_c5_p2_lname',
			82151498 => 'panel_c5_p2_pronoun',
			82151499 => 'panel_c5_p2_email',
			82151500 => 'panel_c5_p2_affiliation',
			82151501 => 'panel_c5_p2_bio',
			82151502 => 'panel_c5_p2_city',
			82151503 => 'panel_c5_p2_country',
			82151504 => 'panel_c5_p2_gender',
			82151505 => 'panel_c5_p2_equity',
			81376245 => 'workshop_title',
			81376246 => 'workshop_description',
			81376247 => 'workshop_lang',
			81376248 => 'workshop_intro',
			82159848 => 'workshop_theme',
			82159860 => 'workshop_orientation',
			81376250 => 'workshop_panelists',
			82151315 => 'workshop_chair_fname',
			82151316 => 'workshop_chair_lname',
			82151317 => 'workshop_chair_email',
			82151623 => 'workshop_p1_fname',
			82151624 => 'workshop_p1_lname',
			82151625 => 'workshop_p1_pronoun',
			82151626 => 'workshop_p1_email',
			82151627 => 'workshop_p1_affiliation',
			82151628 => 'workshop_p1_bio',
			82151629 => 'workshop_p1_city',
			82151630 => 'workshop_p1_country',
			82151631 => 'workshop_p1_gender',
			82151632 => 'workshop_p1_equity',
			82151634 => 'workshop_p2_fname',
			82151635 => 'workshop_p2_lname',
			82151636 => 'workshop_p2_pronoun',
			82151637 => 'workshop_p2_email',
			82151638 => 'workshop_p2_affiliation',
			82151639 => 'workshop_p2_bio',
			82151640 => 'workshop_p2_city',
			82151641 => 'workshop_p2_country',
			82151642 => 'workshop_p2_gender',
			82151643 => 'workshop_p2_equity',
			82151741 => 'workshop_p3_fname',
			82151742 => 'workshop_p3_lname',
			82151743 => 'workshop_p3_pronoun',
			82151744 => 'workshop_p3_email',
			82151745 => 'workshop_p3_affiliation',
			82151746 => 'workshop_p3_bio',
			82151747 => 'workshop_p3_city',
			82151748 => 'workshop_p3_country',
			82151749 => 'workshop_p3_gender',
			82151750 => 'workshop_p3_equity',
			82151752 => 'workshop_p4_fname',
			82151753 => 'workshop_p4_lname',
			82151754 => 'workshop_p4_pronoun',
			82151755 => 'workshop_p4_email',
			82151756 => 'workshop_p4_affiliation',
			82151757 => 'workshop_p4_bio',
			82151758 => 'workshop_p4_city',
			82151759 => 'workshop_p4_country',
			82151760 => 'workshop_p4_gender',
			82151761 => 'workshop_p4_equity',
			82151763 => 'workshop_p5_fname',
			82151764 => 'workshop_p5_lname',
			82151765 => 'workshop_p5_pronoun',
			82151766 => 'workshop_p5_email',
			82151767 => 'workshop_p5_affiliation',
			82151768 => 'workshop_p5_bio',
			82151769 => 'workshop_p5_city',
			82151770 => 'workshop_p5_country',
			82151771 => 'workshop_p5_gender',
			82151772 => 'workshop_p5_equity',
			81274666 => 'email',
			81274791 => 'name',
			81390236 => 'info'
		)
	);
	return $array[$lang][$id];
}

class Import_model extends CI_Model {
	
	public function import($form_id,$lang) {
		$client = new Client(['base_uri' => 'https://www.formstack.com/api/v2/', 'query' => 'oauth_token=cbab05f58ecaf7b5c2f96aa483d0a05d']);
		
		$response = $client->get('form/'.$form_id.'/submission');
		$subm_index = array();
		$init = json_decode($response->getBody(), true);
		for($i=1;$i<=$init['pages'];$i++) {
			$page = array();
			$response = $client->request('GET','form/'.$form_id.'/submission', ['query' => 'oauth_token=cbab05f58ecaf7b5c2f96aa483d0a05d&page='.$i]);
			$page = json_decode($response->getBody(), true);
			foreach ($page['submissions'] as $p) {
				array_push($subm_index,$p['id']);
				
			}
		}
		
		for ($i=0;$i<$init['total'];$i++) {
			$response = $client->get('submission/'.$subm_index[$i]);
			$json = json_decode($response->getBody(), true);			$subm_data = array('type' => '','comm_title' => '','comm_description' => '','comm_lang' => '','comm_intro' => '','comm_theme' => '','comm_orientation' => '','comm_panelists' => '','comm_p1_fname' => '','comm_p1_lname' => '','comm_p1_pronoun' => '','comm_p1_email' => '','comm_p1_affiliation' => '','comm_p1_bio' => '','comm_p1_city' => '','comm_p1_country' => '','comm_p1_gender' => '','comm_p1_equity' => '','comm_p2_fname' => '','comm_p2_lname' => '','comm_p2_pronoun' => '','comm_p2_email' => '','comm_p2_affiliation' => '','comm_p2_bio' => '','comm_p2_city' => '','comm_p2_country' => '','comm_p2_gender' => '','comm_p2_equity' => '','comm_p3_fname' => '','comm_p3_lname' => '','comm_p3_pronoun' => '','comm_p3_email' => '','comm_p3_affiliation' => '','comm_p3_bio' => '','comm_p3_city' => '','comm_p3_country' => '','comm_p3_gender' => '','comm_p3_equity' => '','comm_p4_fname' => '','comm_p4_lname' => '','comm_p4_pronoun' => '','comm_p4_email' => '','comm_p4_affiliation' => '','comm_p4_bio' => '','comm_p4_city' => '','comm_p4_country' => '','comm_p4_gender' => '','comm_p4_equity' => '','panel_title' => '','panel_description' => '','panel_lang' => '','panel_intro' => '','panel_theme' => '','panel_orientation' => '','panel_comms' => '','panel_chair_fname' => '','panel_chair_lname' => '','panel_chair_email' => '','panel_c1_title' => '','panel_c1_description' => '','panel_c1_lang' => '','panel_c1_theme' => '','panel_c1_orientation' => '','panel_c1_panelists' => '','panel_c1_p1_fname' => '','panel_c1_p1_lname' => '','panel_c1_p1_pronoun' => '','panel_c1_p1_email' => '','panel_c1_p1_affiliation' => '','panel_c1_p1_bio' => '','panel_c1_p1_city' => '','panel_c1_p1_country' => '','panel_c1_p1_gender' => '','panel_c1_p1_equity' => '','panel_c1_p2_fname' => '','panel_c1_p2_lname' => '','panel_c1_p2_pronoun' => '','panel_c1_p2_email' => '','panel_c1_p2_affiliation' => '','panel_c1_p2_bio' => '','panel_c1_p2_city' => '','panel_c1_p2_country' => '','panel_c1_p2_gender' => '','panel_c1_p2_equity' => '','panel_c2_title' => '','panel_c2_description' => '','panel_c2_lang' => '','panel_c2_theme' => '','panel_c2_orientation' => '','panel_c2_panelists' => '','panel_c2_p1_fname' => '','panel_c2_p1_lname' => '','panel_c2_p1_pronoun' => '','panel_c2_p1_email' => '','panel_c2_p1_affiliation' => '','panel_c2_p1_bio' => '','panel_c2_p1_city' => '','panel_c2_p1_country' => '','panel_c2_p1_gender' => '','panel_c2_p1_equity' => '','panel_c2_p2_fname' => '','panel_c2_p2_lname' => '','panel_c2_p2_pronoun' => '','panel_c2_p2_email' => '','panel_c2_p2_affiliation' => '','panel_c2_p2_bio' => '','panel_c2_p2_city' => '','panel_c2_p2_country' => '','panel_c2_p2_gender' => '','panel_c2_p2_equity' => '','panel_c3_title' => '','panel_c3_description' => '','panel_c3_lang' => '','panel_c3_theme' => '','panel_c3_orientation' => '','panel_c3_panelists' => '','panel_c3_p1_fname' => '','panel_c3_p1_lname' => '','panel_c3_p1_pronoun' => '','panel_c3_p1_email' => '','panel_c3_p1_affiliation' => '','panel_c3_p1_bio' => '','panel_c3_p1_city' => '','panel_c3_p1_country' => '','panel_c3_p1_gender' => '','panel_c3_p1_equity' => '','panel_c3_p2_fname' => '','panel_c3_p2_lname' => '','panel_c3_p2_pronoun' => '','panel_c3_p2_email' => '','panel_c3_p2_affiliation' => '','panel_c3_p2_bio' => '','panel_c3_p2_city' => '','panel_c3_p2_country' => '','panel_c3_p2_gender' => '','panel_c3_p2_equity' => '','panel_c4_title' => '','panel_c4_description' => '','panel_c4_lang' => '','panel_c4_theme' => '','panel_c4_orientation' => '','panel_c4_panelists' => '','panel_c4_p1_fname' => '','panel_c4_p1_lname' => '','panel_c4_p1_pronoun' => '','panel_c4_p1_email' => '','panel_c4_p1_affiliation' => '','panel_c4_p1_bio' => '','panel_c4_p1_city' => '','panel_c4_p1_country' => '','panel_c4_p1_gender' => '','panel_c4_p1_equity' => '','panel_c4_p2_fname' => '','panel_c4_p2_lname' => '','panel_c4_p2_pronoun' => '','panel_c4_p2_email' => '','panel_c4_p2_affiliation' => '','panel_c4_p2_bio' => '','panel_c4_p2_city' => '','panel_c4_p2_country' => '','panel_c4_p2_gender' => '','panel_c4_p2_equity' => '','panel_c5_title' => '','panel_c5_description' => '','panel_c5_lang' => '','panel_c5_theme' => '','panel_c5_orientation' => '','panel_c5_panelists' => '','panel_c5_p1_fname' => '','panel_c5_p1_lname' => '','panel_c5_p1_pronoun' => '','panel_c5_p1_email' => '','panel_c5_p1_affiliation' => '','panel_c5_p1_bio' => '','panel_c5_p1_city' => '','panel_c5_p1_country' => '','panel_c5_p1_gender' => '','panel_c5_p1_equity' => '','panel_c5_p2_fname' => '','panel_c5_p2_lname' => '','panel_c5_p2_pronoun' => '','panel_c5_p2_email' => '','panel_c5_p2_affiliation' => '','panel_c5_p2_bio' => '','panel_c5_p2_city' => '','panel_c5_p2_country' => '','panel_c5_p2_gender' => '','panel_c5_p2_equity' => '','workshop_title' => '','workshop_description' => '','workshop_lang' => '','workshop_intro' => '','workshop_theme' => '','workshop_orientation' => '','workshop_panelists' => '','workshop_chair_fname' => '','workshop_chair_lname' => '','workshop_chair_email' => '','workshop_p1_fname' => '','workshop_p1_lname' => '','workshop_p1_pronoun' => '','workshop_p1_email' => '','workshop_p1_affiliation' => '','workshop_p1_bio' => '','workshop_p1_city' => '','workshop_p1_country' => '','workshop_p1_gender' => '','workshop_p1_equity' => '','workshop_p2_fname' => '','workshop_p2_lname' => '','workshop_p2_pronoun' => '','workshop_p2_email' => '','workshop_p2_affiliation' => '','workshop_p2_bio' => '','workshop_p2_city' => '','workshop_p2_country' => '','workshop_p2_gender' => '','workshop_p2_equity' => '','workshop_p3_fname' => '','workshop_p3_lname' => '','workshop_p3_pronoun' => '','workshop_p3_email' => '','workshop_p3_affiliation' => '','workshop_p3_bio' => '','workshop_p3_city' => '','workshop_p3_country' => '','workshop_p3_gender' => '','workshop_p3_equity' => '','workshop_p4_fname' => '','workshop_p4_lname' => '','workshop_p4_pronoun' => '','workshop_p4_email' => '','workshop_p4_affiliation' => '','workshop_p4_bio' => '','workshop_p4_city' => '','workshop_p4_country' => '','workshop_p4_gender' => '','workshop_p4_equity' => '','workshop_p5_fname' => '','workshop_p5_lname' => '','workshop_p5_pronoun' => '','workshop_p5_email' => '','workshop_p5_affiliation' => '','workshop_p5_bio' => '','workshop_p5_city' => '','workshop_p5_country' => '','workshop_p5_gender' => '','workshop_p5_equity' => '','email' => '','name' => '','info' => '');
			foreach ($json['data'] as $s) {
				$subm_data[toFieldName($s['field'],$lang)] = $s['value'];
			}
			$user_id = '';
			$query = $this->db->get_where('user', array('user_email' => $subm_data['email']));
			if ($r = $query->result_array()) {
				$user_id .= $r[0]['user_id'];
			} else {
				$user = array('user_id' => '','user_name' => $subm_data['name'],'user_email' => $subm_data['email'],'user_password' => '1A2B3C!','user_role' => 4,'user_meta' => '');
				$this->db->insert('user', $user);
				$user_id .= $this->db->insert_id();
			}
			$subm = array('',$json['timestamp']);
			if ($subm_data['type']=='Communication') {
				array_push($subm,slugify($subm_data['comm_title']),textHTML($subm_data['comm_title']),textHTML($subm_data['comm_description']),textHTML($subm_data['comm_lang']),textHTML($subm_data['comm_intro']),textHTML($subm_data['comm_theme']),textHTML($subm_data['comm_orientation']), 0, 0, 0, $user_id, '');
				$this->db->insert('subm', associateSubm($subm,$lang));
				$subm_id = '';
				$subm_id .= $this->db->insert_id();
				for ($x=1; $x<=$subm_data['comm_panelists']; $x++) {
					$p = 'comm_p'.$x.'_';
					$part = array('');
					$pslug = $subm_data[$p.'fname'].' '.$subm_data[$p.'lname'].' '.$subm_data[$p.'city'];
					array_push($part,slugify($pslug),textHTML($subm_data[$p.'fname']),textHTML($subm_data[$p.'lname']),textHTML($subm_data[$p.'pronoun']),textHTML($subm_data[$p.'email']),textHTML($subm_data[$p.'affiliation']),textHTML($subm_data[$p.'bio']),textHTML($subm_data[$p.'city']),textHTML($subm_data[$p.'country']),textHTML($subm_data[$p.'gender']),textHTML($subm_data[$p.'equity']), $user_id, $subm_id, '');
					$this->db->insert('part', associatePart($part));
					$part_id = '';
					$part_id .= $this->db->insert_id();
					$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
					$this->db->insert('part_subm', $part_subm);
				}
			} else if ($subm_data['type']=='Panel (ou séminaire)' || $subm_data['type']=='Panel (or Seminar)') {
				array_push($subm,slugify($subm_data['panel_title']),textHTML($subm_data['panel_title']),textHTML($subm_data['panel_description']),textHTML($subm_data['panel_lang']),textHTML($subm_data['panel_intro']),textHTML($subm_data['panel_theme']),textHTML($subm_data['panel_orientation']), 1, 0, 0, $user_id, '');
				$this->db->insert('subm', associateSubm($subm,$lang));
				$panel_id = '';
				$panel_id .= $this->db->insert_id();
				$p = 'panel_chair_';
				$part = array('');
				$pslug = $subm_data[$p.'fname'].' '.$subm_data[$p.'lname'].' '.$panel_id;
				array_push($part,slugify($pslug),textHTML($subm_data[$p.'fname']),textHTML($subm_data[$p.'lname']),'',textHTML($subm_data[$p.'email']),'','','','','','',$user_id,$panel_id,'');
				$this->db->insert('part', associatePart($part));
				$part_id = '';
				$part_id .= $this->db->insert_id();
				$part_subm = array('part_id' => $part_id, 'subm_id' => $panel_id, 'part_subm_type' => 1);
				$this->db->insert('part_subm', $part_subm);
				for ($y=1;$y<=$subm_data['panel_comms'];$y++) {
					$c = 'panel_c'.$y.'_';
					$comm = array('',$json['timestamp'],slugify($subm_data[$c.'title']),textHTML($subm_data[$c.'title']),textHTML($subm_data[$c.'description']),textHTML($subm_data[$c.'lang']),'NA',textHTML($subm_data[$c.'theme']),textHTML($subm_data[$c.'orientation']), 0, 0, $panel_id, $user_id, '');
					$this->db->insert('subm', associateSubm($comm,$lang));
					$subm_id = '';
					$subm_id .= $this->db->insert_id();
					for ($x=1; $x<=$subm_data[$c.'panelists']; $x++) {
						$p = 'panel_c'.$y.'_p'.$x.'_';
						$part = array('');
						$pslug = $subm_data[$p.'fname'].' '.$subm_data[$p.'lname'].' '.$subm_data[$p.'city'];
						array_push($part,slugify($pslug),textHTML($subm_data[$p.'fname']),textHTML($subm_data[$p.'lname']),textHTML($subm_data[$p.'pronoun']),textHTML($subm_data[$p.'email']),textHTML($subm_data[$p.'affiliation']),textHTML($subm_data[$p.'bio']),textHTML($subm_data[$p.'city']),textHTML($subm_data[$p.'country']),textHTML($subm_data[$p.'gender']),textHTML($subm_data[$p.'equity']),$user_id, $subm_id, '');
						$this->db->insert('part', associatePart($part));
						$part_id = '';
						$part_id .= $this->db->insert_id();
						$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
						$this->db->insert('part_subm', $part_subm);
					}
				}
			} else if ($subm_data['type']=='Table ronde (ou atelier)' || $subm_data['type']=='Round Table (or Workshop)') {
				array_push($subm,slugify($subm_data['workshop_title']),textHTML($subm_data['workshop_title']),textHTML($subm_data['workshop_description']),textHTML($subm_data['workshop_lang']),textHTML($subm_data['workshop_intro']),textHTML($subm_data['workshop_theme']),textHTML($subm_data['workshop_orientation']),2,0,0,$user_id,'');
				$this->db->insert('subm', associateSubm($subm,$lang));
				$subm_id = '';
				$subm_id .= $this->db->insert_id();
				$p = 'workshop_chair_';
				$part = array('');
				$pslug = $subm_data[$p.'fname'].' '.$subm_data[$p.'lname'].' '.$subm_id;
				array_push($part,slugify($pslug),textHTML($subm_data[$p.'fname']),textHTML($subm_data[$p.'lname']),'',textHTML($subm_data[$p.'email']),'','','','','','',$user_id,$subm_id,'');
				$this->db->insert('part', associatePart($part));
				$part_id = '';
				$part_id .= $this->db->insert_id();
				$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 1);
				$this->db->insert('part_subm', $part_subm);
				for ($x=1; $x<=$subm_data['workshop_panelists']; $x++) {
					$p = 'workshop_p'.$x.'_';
					$part = array('');
					$pslug = $subm_data[$p.'fname'].' '.$subm_data[$p.'lname'].' '.$subm_data[$p.'city'];
					array_push($part,slugify($pslug),textHTML($subm_data[$p.'fname']),textHTML($subm_data[$p.'lname']),textHTML($subm_data[$p.'pronoun']),textHTML($subm_data[$p.'email']),textHTML($subm_data[$p.'affiliation']),textHTML($subm_data[$p.'bio']),textHTML($subm_data[$p.'city']),textHTML($subm_data[$p.'country']),textHTML($subm_data[$p.'gender']),textHTML($subm_data[$p.'equity']), $user_id, $subm_id, '');
					$this->db->insert('part', associatePart($part));
					$part_id = '';
					$part_id .= $this->db->insert_id();
					$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
					$this->db->insert('part_subm', $part_subm);
				}
			}
		}
		return 'Done';
	}
}