<?php namespace Indoraptor\Helper;

class WebsiteReportController extends \Indoraptor\IndoController
{
    public function total()
    {
        $stats = \array_fill(0, 6, array());

        $query = "SELECT COUNT(*) FROM default_log";
        $res = $this->conn->query($query);        
        $amount = $res ? $res->fetchColumn() : 0;
        $stats[0] = array('percent' => 100, 'amount' => $amount);
        
        $query_pages = "SELECT COUNT(*) FROM default_log WHERE Object = 'pages'";
        $res_pages = $this->conn->query($query_pages);        
        $amount_pages = $res_pages ? $res_pages->fetchColumn() : 0;
        $percent_pages = ($amount_pages * 100 / $amount);
        $stats[2] = array('percent' => $percent_pages, 'amount' => $amount_pages);

        $query_news = "SELECT COUNT(*) FROM default_log WHERE Object = 'news'";
        $res_news = $this->conn->query($query_news);        
        $amount_news = $res_news ? $res_news->fetchColumn() : 0;
        $percent_news = ($amount_news * 100 / $amount);
        $stats[3] = array('percent' => $percent_news, 'amount' => $amount_news);

        $amount_home = $amount - $amount_pages - $amount_news;
        $percent_home = ($amount_home * 100 / $amount);
        $stats[1] = array('percent' => $percent_home, 'amount' => $amount_home);

        $query_mn = "SELECT COUNT(*) FROM default_log WHERE Language = 'mn'";
        $res_mn = $this->conn->query($query_mn);        
        $amount_mn = $res_mn ? $res_mn->fetchColumn() : 0;
        $percent_mn = ($amount_mn * 100 / $amount);
        $stats[4] = array('percent' => $percent_mn, 'amount' => $amount_mn);
        
        $amount_en = $amount - $amount_mn;
        $percent_en = ($amount_en * 100 / $amount);
        $stats[5] = array('percent' => $percent_en, 'amount' => $amount_en);
        
        $this->respond($stats);
    }
    
    public function daily()
    {
        $daily = array();
        
        $query = "SELECT Count(*) as count, date(Time) as date  FROM default_log GROUP BY date(Time)";
        foreach ($this->conn->query($query) as $row) {
            $daily[1][] = array(
                'date' => $row['date'],
                'value' => $row['count'],
                'volume' => $row['count'],
                'color' => '#d9534f'
            );
        }
        
        $query = "SELECT Count(*) as count, date(Time) as date  FROM default_log WHERE URL = '/' or URL = '/?' GROUP BY date(Time)";
        foreach ($this->conn->query($query) as $row) {
            $daily[2][] = array(
                'date' => $row['date'],
                'value' => $row['count'],
                'volume' => $row['count'],
                'color' => '#292b2c'
            );
        }


        $query = "SELECT Count(*) as count, date(Time) as date  FROM default_log WHERE Object = 'pages' GROUP BY date(Time)";
        foreach ($this->conn->query($query) as $row) {
            $daily[3][] = array(
                'date' => $row['date'],
                'value' => $row['count'],
                'volume' => $row['count'],
                'color' => '#5bc0de'
            );
        }

        $query = "SELECT Count(*) as count, date(Time) as date  FROM default_log WHERE Object = 'news' GROUP BY date(Time)";
        foreach ($this->conn->query($query) as $row) {
            $daily[4][] = array(
                'date' => $row['date'],
                'value' => $row['count'],
                'volume' => $row['count'],
                'color' => '#f0ad4e'
            );
        }

        $this->respond($daily);
    }
}
