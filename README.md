# Some-miscellaneous-functions



## NESTED FUNCTION IF WE DONT HAVE DEFINIED CHILDS LIKE TREE
```php
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
```


## DATE FORMATE CHANGE FROM 2018/10/04 TO 2018-10-04
```php
function date_yyyy_mm_dd($var = '') {
    $date = str_replace('/', '-', $var);
    return date('Y-m-d', strtotime($date));
}

```

## GET NUMBER OF DAYS BETWEEN TWO DATES EX: 2018-09-10, 2018-10-02 $cond_ar will be day ids like 1= monday
```php
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
```
## GET NUMBER OF DAYS BETWEEN TWO DATES EX: 2018-09-10, 2018-10-02
```php
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
```
## TIME DIFFERENCE IN MINUTES
```php
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
```
## Draw PHP CALENDAR BY JUST PASSING START AND END DATE
```php
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
```

```php
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
```