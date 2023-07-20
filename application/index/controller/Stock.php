<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Stock extends Controller
{
    public function list(){

        $time            = self::getStartAndEnd('last_6_month');

        $monthStr  = self::getMonthStr($time);
        $monthList = [];
        foreach ($monthStr as $month) {
            $monthList[$month] = [
                'month'    => $month,
                'sale_num' => 0,

            ];
        }
returnJson($monthList);
        $salesList       = Db::table('goods_monthly_sales')->select();
        $saleListMonth = [];
        foreach ($salesList as $sale) {
            $keyM = "{$sale['warehouse_id']}_{$sale['department_id']}_{$sale['goods_id']}";
            if (!isset($saleListMonth[$keyM])) {
                $saleListMonth[$keyM]['goods_id'] = $sale['goods_id'];
                $saleListMonth[$keyM]['warehouse_id'] = $sale['warehouse_id'];
                $saleListMonth[$keyM]['department_id'] = $sale['department_id'];
                $saleListMonth[$keyM]['list'] = $monthList;
            }
            $saleListMonth[$keyM]['list'][$sale['month']] = [
                'month' =>$sale['month'],
                'sale_num' =>$sale['sale_num'],
            ];
        }

        returnJson($saleListMonth);




        $list = Db::table('warehouse_suggest_stock')->select();
        returnJson($list);
        if (!empty($list)) {
//            self::getOperationRecord($list);
            $time            = self::getStartAndEnd('last_6_month');
            return 123;
            $goodsIdArr      = array_column($list, 'goods_id');
            $condition       = [
                ['goods_id', 'in', $goodsIdArr],
                ['month', '>=', date('Ym', $time['start'])],
                ['month', '<=', date('Ym', $time['end'])],
            ];
            $salesList       = self::getSalesList($condition);
            $newSalesList    = [];
            $departmentTotal = [];
return 123;
            $monthStr  = self::getMonthStr($time);
            $monthList = [];
            foreach ($monthStr as $month) {
                $monthList[$month] = [
                    'month'    => $month,
                    'sale_num' => 0,

                ];
            }

            $saleListMonth = [];
            foreach ($salesList as $sale) {
                $keyM = "{$sale['warehouse_id']}_{$sale['department_id']}_{$sale['goods_id']}";
                if (!isset($saleListMonth[$keyM])) {
                    $saleListMonth[$keyM] = $monthList;
                }
                $saleListMonth[$keyM][$sale['month']] = $sale;
            }

            foreach ($salesList as $value) {
                $newSalesList[$value['warehouse_id'] . '_' . $value['goods_id']][] = $value;
                $warehouseId                                                       = $value['warehouse_id'];
                $goodsId                                                           = $value['goods_id'];

                $month = $value['month'];
                $tKey  = "{$warehouseId}_{$goodsId}";
                if (!isset($departmentTotal[$tKey])) {
                    $departmentTotal[$tKey] = [];
                }
                !isset($departmentTotal[$tKey][$month]) && $departmentTotal[$tKey][$month] = 0;
                $departmentTotal[$tKey][$month] += $value['sale_num'];
            }


//            $departmentMapping = WookDepartmentDao::getMapping();

            foreach ($list as &$item) {

                $body          = $newSalesList[$item['warehouse_id'] . '_' . $item['goods_id']];
                $item['total'] = $departmentTotal[$item['warehouse_id'] . '_' . $item['goods_id']] ?? [];
                $newBody       = [];
                foreach ($body as $value) {
                    $newBody[$value['department_id']]['id']     = $value['department_id'];
//                    $newBody[$value['department_id']]['name']   = $departmentMapping[$value['department_id']] ?? '';
                    $newBody[$value['department_id']]['list'][] = [
                        'month'    => $value['month'],
                        'sale_num' => $value['sale_num'],
                    ];
                }
                foreach ($newBody as &$sub) {
                    $total                  = [
                        'id'              => 0,
                        "name"            => "",
                        "list"            => [],
                        "num_6_month"     => 0,
                        "avg_num_6_month" => 0
                    ];
                    $subList                = $sub['list'];
                    $sub['num_6_month']     = array_sum(array_column($subList, 'sale_num'));
                    $sub['avg_num_6_month'] = floor(array_sum(array_column($subList, 'sale_num')) / 6);
//
                    $monthList = array_column($subList, 'month');
                    foreach ($monthStr as $month) {
                        if (!in_array($month, $monthList)) {
                            array_push($subList, [
                                'month'    => $month,
                                'sale_num' => 0,
                            ]);
                        }
                    }
                    $keyMonth = array_column($subList, 'month');
                    array_multisort($keyMonth, SORT_ASC, $subList);
                    $sub['list'] = $subList;

                }

                $item['body'] = array_values($newBody);
            }
        }



    }

    public static function getMonthStr($time)
    {
        $monthArr = [];
        $i        = 0;
        do {
            $month      = date('Ym', strtotime('+' . $i . ' month', date($time['start'])));
            $monthArr[] = $month;
            $i++;
        } while ($month < date('Ym', $time['end']));
        rsort($monthArr);
        return $monthArr;
    }

    /**
     * 获取开始和结束时间
     */
    public static function getStartAndEnd($key = '')
    {
        if (!in_array($key, ['this_month', 'last_month', 'last_3_month', 'last_6_month', 'last_180_day'])) {
            $key = 'last_6_month';
        }
        date_default_timezone_set('Asia/Shanghai');
        if ($key == 'last_6_month') {//不包含当月的前六个月的起始时间
            $start = mktime(0, 0, 0, date('m') - 6, 1, date('Y'));
            $end   = mktime(23, 59, 59, date('m') - 1, date('t', strtotime("last month")), date('Y'));
        } elseif ($key == 'last_180_day') {//包含当月的前180天的起始时间
            $start = mktime(0, 0, 0, date('m'), date('d') - 30 * 6, date('Y'));
            $end   = mktime(0, 0, 0, date('m'), date('d'), date('y')) - 1;
        } elseif ($key == 'this_month') {//当月截至今天起始时间(不含当天)
            $start = strtotime(date('Y-m-1', time()));
            $end   = strtotime(date('Y-m-d', time())) - 1;
        } elseif ($key == 'last_3_month') { // 前三个月
            $start = mktime(0, 0, 0, date('m') - 3, 1, date('Y'));
            $end   = mktime(23, 59, 59, date('m') - 1, date('t', strtotime("last month")), date('Y'));
        } elseif ($key == 'last_month') { // 前个月
            $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
            $end   = mktime(23, 59, 59, date('m') - 1, date('t', strtotime("last month")), date('Y'));
        }
        return [
            'start' => $start,
            'end'   => $end
        ];
    }


    public static function getSalesList($condition,$field = ['*']){
       return  Db::table('goods_monthly_sales')->where($condition)->select();

    }
}