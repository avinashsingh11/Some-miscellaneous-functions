<?php

if (!function_exists('show_route')) {

    function show_route() {
        echo "Test1";
    }

}
if (!function_exists('from_model')) {

    function from_model($req) {
        $form = '';
        if ($req->requirement_type == '1') {//Text
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label>';
            $form .='<input class="form-control" type="text" name="extraform[' . $req->input_name . ']" required>';
            $form .= '<label for="username" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '2') {//Checkbox
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label><br>';
            if ($req->options) {
                $options = explode(',', $req->options);
                foreach ($options as $option) {
                    $form .='<label  class="control-label"><input class="form-control" type="checkbox" name="extraform[' . $option . ']">' . $option . '</label>';
                }
            }
            $form .= '<br><label for="username" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '3') {//Radio//
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label><br>';
            $optionsArr = explode(',', $req->options);
            $ik = 0;
            foreach ($optionsArr as $option) {
                $opt_val = explode(':', $option);
                $requ = ($ik == 0) ? 'required' : '';
                $form .='<label  class="control-label"><input name="extraform[' . $req->input_name . ']" class="" type="radio" value="' . $opt_val[1] . '" ' . $requ . '>' . $opt_val[0] . '</label>';
                $ik++;
            }
            $form .= '<br><label for="username" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '4') {//Textarea
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label>';
            $form .='<textarea class="form-control" type="text" name="extraform[' . $req->input_name . ']"></textarea>';
            $form .= '<label for="username" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '5') {//File
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label>';
            $form .='<input class="form-control" type="file" name="extraform[' . $req->input_name . ']">';
            $form .= '<label for="username" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '6') {//Dropdown
            $form = '<div class="form-group "><label for="' . str_slug($req->title) . '" class="control-label">' . $req->title . '</label><br>';
            $form .='<select id="' . str_slug($req->title) . '" class="form-control" name="extraform[' . $req->input_name . ']" required>';
            if ($req->options) {
                $options = explode(',', $req->options);
                $form .='<option value="">Please Select--</option>';
                foreach ($options as $option) {
                    $form .='<option value="' . $option . '">' . $option . '</option>';
                }
            }
            $form .= '</select><label for="' . str_slug($req->title) . '" class="help-block">' . $req->help_block . '</label></div>';
        } elseif ($req->requirement_type == '7') {//Date Range
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label>';
            $form .='<div class="input-group"><div class="input-group-addon"><$i class="fa fa-calendar"></$i></div>';
            $form .='<input type="text" name="extraform[' . $req->input_name . ']" class="form-control pull-right" id="reservation">';
            $form .= '<label for="username" class="help-block">' . $req->help_block . '</label></div></div>';
        } elseif ($req->requirement_type == '8') {//Date Time Range
            $form = '<div class="form-group"><label for="username" class="control-label">' . $req->title . '</label>';
            $form .='<div class="input-group"><div class="input-group-addon"><$i class="fa fa-clock-o"></$i></div>';
            $form .='<input type="text" name="extraform[' . $req->input_name . ']" class="form-control pull-right" id="reservationtime">';
            $form .= '<label for="username" class="help-block">' . $req->help_block . '</label></div></div>';
        }
        return $form;
    }

}

if (!function_exists('get_settings')) {

    function get_settings() {
        //return 'ab';
        $queries = DB::select("select * from osp_settings");
        return array_pluck($queries, 'option_value', 'option_name');
    }

}
if (!function_exists('get_setting_single')) {

    function get_setting_single($key) {
        $queries = DB::select("select * from osp_settings");
        $dd = array_pluck($queries, 'option_value', 'option_name');
        return @$dd[$key];
    }

}

if (!function_exists('job_count')) {

    function job_count($id) {
        $queries = DB::select("select category_id from osp_job WHERE category_id = $id");
        return count($queries);
    }

}

function nested2table($array, $level = 0) {
    /* echo "<pre>";print_r($array);
      echo "</pre>";exit(); */
    $result = array();
    if (is_array($array) && count($array) > 0) {
        $result[] = '';
        foreach ($array as $value) {
            DB::table('osp_terms')->where('id', $value['id'])->update(['count' => job_count($value['id'])]);
            $str = str_repeat("— ", $level);
            $lnk = '<a href="' . url('admin/edit_category/' . $value['id']) . '"><i class="fa fa-edit btn btn-primary"></i> &nbsp&nbsp&nbsp&nbsp <a href="' . url('admin/delete_category/' . $value['id']) . '" onClick="return doconfirm();"><i class="fa fa-remove btn btn-danger"></i></a>';
            $result[] = sprintf(
                    '<tr><td><img src="%s" width="80px"></td><td>%s%s</td><td>%s </td><td>%s </td><td>%s </td></tr>%s', asset("public/uploads/category/" . $value['image']), $str, $value['name'],
                    //$value['description'],
                    $value['slug'], $value['count'], $lnk, nested2table(@$value['taxonomy'], $level + 1)
            );
        }
        $result[] = '';
    } else {
        $level = $level + 1;
    }


    return implode($result);
}

function nested2option($array, $level = 0, $term_id = '') {
    /* echo "<pre>";print_r($array);
      echo "</pre>";exit(); */
    $result = array();
    if (is_array($array) && count($array) > 0) {
        $result[] = '';
        foreach ($array as $value) {
            $str = str_repeat("— ", $level);
            $select = $term_id == $value['id'] ? 'selected' : '';
            $result[] = sprintf(
                    '<option value="%s" %s>%s%s</option>%s', $value['id'], $select, $str, $value['name'], nested2option(@$value['taxonomy'], $level + 1, $term_id)
            );
        }
        $result[] = '';
    }


    return implode($result);
}

function nested2listitems($array, $level = 0, $cat = []) {
    /* echo "<pre>";print_r($array);
      echo "</pre>";exit(); */
    $result = array();
    if (is_array($array) && count($array) > 0) {
        $result[] = '';
        foreach ($array as $value) {
            $str = str_repeat("— ", $level);
            $check = in_array($value['id'], $cat) ? 'checked' : '';
            $result[] = sprintf(
                    '<li>%s<input name="category[]" type="checkbox" class="minimal" value="%s" id="%s"%s><label for="%s">%s</label></li>%s', $str, $value['id'], $value['slug'], $check, $value['slug'], $value['name'], nested2listitems(@$value['taxonomy'], $level + 1, $cat)
            );
        }
        $result[] = '';
    }


    return implode($result);
}

function pr($ar = []) {
    echo "<pre>";
    print_r($ar);
    echo "</pre>";
}

function date_yyyy_mm_dd($var = '') {
    $date = str_replace('/', '-', $var);
    return date('Y-m-d', strtotime($date));
}

function date_difference($from = '', $to = '', $arg = 'days') {

    $curdate = strtotime($to);
    $mydate = strtotime($from);
    $to = date_create($to);
    $from = date_create($from);
    $diff_in_days = date_diff($to, $from);
//pr($diff_in_days);
    $days = 0;
    if ($arg == 'days') {
        $days = $diff_in_days->days + 1;
    }

    if ($curdate < $mydate) {
        return 0 - $days;
    } else {
        return $days;
    }
}

function date_two_time_compare($from = '', $to = '') {

    $curdate = strtotime($to);
    $mydate = strtotime($from);

    if ($curdate < $mydate) {
        return true;
    } else {
        return false;
    }
}

function date_difference_with_condition($from = '', $to = '', $cond_ar = [], $type = 'weekly') {
    $total = 0;
    $from = strtotime($from);
    $to = strtotime($to);
    if ($type == 'daily') {
        for ($i = $from; $i <= $to; $i+=86400) {
            $total++;
        }
    } else {
        foreach ($cond_ar as $dd) {
            for ($i = $from; $i <= $to; $i+=86400) {
                if ($type == 'weekly') {
                    $chk = date('w', $i);
                } elseif ($type == 'monthly') {
                    $chk = date('d', $i);
                } elseif ($type == 'daily') {
                    $chk = 0;
                    $total++;
                }
                if ($chk == $dd) {
                    $total++;
                }
                //echo date("Y-m-d", $i).'<br />';  
            }
        }
    }
    return $total;
}

function weekly_array_exists($from = '', $to = '', $cond_ar = []) {
   
    $from = strtotime($from);
    $to = strtotime($to);
   $return_ar = [];
    foreach ($cond_ar as $dd) {
        for ($i = $from; $i <= $to; $i+=86400) {
            
            $chk = date('w', $i);           
            if ($chk == $dd) {
                if (!in_array($dd+1, $return_ar)) {
                    $return_ar[] = $dd+1;
                    
                }
            }
            //echo date("Y-m-d", $i).'<br />';  
        }
    }
  
    return $return_ar;
}


function get_days_array_between_days($from = '', $to = '') {
   
    $from = strtotime($from);
    $to = strtotime($to);
    $ar_return = [];
        for ($i = $from; $i <= $to; $i+=86400) {
            
            $chk = date('w', $i);           
            if (!in_array($chk+1, $ar_return)) {
                $ar_return[] = $chk+1;
            }
            
            //echo date("Y-m-d", $i).'<br />';  
      }
    return $ar_return;
}

function timedifference_in_minutes($start_time, $end_time,$para="") {
    /*$first_date = new DateTime("2012-11-30 " . $start_time);
    $second_date = new DateTime("2012-12-21 " . $end_time);
    $difference = $first_date->diff($second_date);
    //pr($difference);
    return $total_minutes = $difference->h * 60 + $difference->$i;*/
    $start_time = date("G:i", strtotime($start_time));
    $end_time = date("G:i", strtotime($end_time));
    $s_spit = explode(":",$start_time);
    $e_spit = explode(":",$end_time);

    $h1 = (int)($s_spit[0]);
    $m1 = (int)($s_spit[1]);

    $h2 = (int)($e_spit[0]);
    $m2 = (int)($e_spit[1]);

    //console.log($h1+' '+$m1+' '+$h2+' ' +$m2);
    $counter = 0;
    if(($h2-$h1)<0){
        for($i=$h1;$i<24;$i++) $counter++;
        for($i=1;$i<=$h2;$i++) $counter++;
    } else if(($h2-$h1)>0){
        $counter = $h2-$h1;
    } else {
        $counter = 24;
    }
    $minutes = 0;
    if(($m2-$m1)<0) {
        $minutes = 0-($m2-$m1);
        $counter--;
    } else {
        $minutes = $m2-$m1;
    }
   if ($para =='hours') {
       return ($counter . " hrs : " . $minutes . " mins");
   }
    return ($counter*60 + $minutes);
   
}

function drawcalendar_onschedule($start_date, $end_date) {
    $weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    $daysss = '';
    foreach ($weekdays as $day) {
        $daysss .= '<div class="week-list"><label">' . $day . '</label></div>';
    }
    $returnStr = $daysss;
    $from = strtotime($start_date);
    $n = date('N', $from);
    $month = (int) date('m', $from);
    $prev_month = (int) date('m', $from) - 1;
    //$to = strtotime('2018-10-30');
    $to = strtotime($end_date);
    $ct = 0;
    for ($i = $from; $i <= $to;) {
        $current_month = (int) date('m', $i);
        $heading = '';
        if ($current_month != $prev_month) {
            $heading = '<p class="monthheading">' . date('F', $i) . '</p>';
            $prev_month = $current_month;
        }
        if ($ct < $n) {
            $msg = $heading . '<div class="week-list">';
            $msg .= ' ';
            $msg .= '<label for="' . date('d', $i) . '"> </label></div>';
        } else {
            $msg = $heading . '<div class="week-list">';
            $msg .= ' <input id="' . date('d', $i) . '" name="monthly[]" type="checkbox" class="form-control days" value="' . date('d', $i) . '" >';
            $msg .= '<label for="' . date('d', $i) . '">' . date('d', $i) . '</label></div>';
            $i+=86400;
        }
        $returnStr .=$msg;
        $ct++;
    }
    return $returnStr;
}

function status_text($id) {
    if ($id == 1) {
        return 'Initiated';
    } elseif ($id == 2) {
        return 'Inprogress';
    } elseif ($id == 3) {
        return 'Completed';
    } elseif ($id == 4) {
        return 'Canceled';
    } elseif ($id == 5) {
        return 'Deleted';
    }
}

function days_text($id) {
    if ($id == 1) {
        return 'Monday';
    } elseif ($id == 2) {
        return 'Tuesday';
    } elseif ($id == 3) {
        return 'Wednesday';
    } elseif ($id == 4) {
        return 'Thurday';
    } elseif ($id == 5) {
        return 'Friday';
    }elseif ($id == 6) {
        return 'Saturday';
    }elseif ($id == 7) {
        return 'Sunday';
    }
}

function hours_format($value=''){
    if ($value !='') {
        return date('g:i A',strtotime($value));
    }
    return '';
}

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}