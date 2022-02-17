<?php

namespace AuLait;

class Paginator
{
    /** @var integer 表示数(項目) */
    public $view_size;

    /** @var integer 検索結果件数 */
    public $result_count;

    /** @var integer 現在のページ */
    public $current_page_num;

    /** @var integer 最後のページ番号 */
    public $end_page_num;

    /** @var integer 表示項目(始端） */
    public $view_start;

    /** @var integer 表示項目(終端） */
    public $view_end;

    /** @var integer 表示ページ(始端) */
    public $page_range_start;

    /** @var integer 表示ページ(終端) */
    public $page_range_end;

    /** @var integer 前の○ページ */
    public $prev_page_num;

    /** @var integer 次の○ページ */
    public $next_page_num;

    /** @var integer ページの表示範囲数 */
    public $page_range;

    /**
     * @param int $page_num
     * @param int $result_count
     * @param int $view_size
     * @param int $page_range
     * @param int $result_limit DBなどの制限でoffsetの最大が決まっている場合に利用。例：Elasticsearch => 10,000
     */
    public function __construct(int $page_num, int $result_count, int $view_size = 10, int  $page_range = 5, int $result_limit=null)
    {
        // 現在のページ番号
        if ($page_num > 0) {
            $this->current_page_num = $page_num;
        } else {
            throw new Exception('a');
        }

        // 検索結果件数
        if ($result_count >= 0) {
            $this->result_count = $result_count;
        } else {
            throw new Exception('$result_count must be greater than 0');
        }

        // ページ毎の項目表示数が０以下の場合はデフォルト値を代入
        if ($view_size > 0) {
            $this->view_size = $view_size;
        } else {
            throw new Exception('c');
        }

        // ページャで表示するページの範囲のサイズ ex: 5なら 3,4,5,6,7
        if ($page_range > 0) {
            $this->page_range = $page_range;
        } else {
            throw new Exception('d');
        }

        // 検索結果件数０の時は何もしない
        if ($result_count == 0) {
            return;
        }

        // 表示データ(始端、終端)
        $this->view_start = ($this->current_page_num - 1) * $this->view_size + 1;
        $this->view_end = ($this->current_page_num - 1) * $this->view_size + $this->view_size;
        if ($this->view_end > $this->result_count) {
            $this->view_end = $this->result_count;
        }
        if ($this->view_start > $this->result_count) {
            $this->current_page_num;
            return;
        }

        // システム上限がある場合はそれを加味する
        if ($result_limit) {
            if ($this->view_end > $this->result_count) {
                throw new Exception('d2');
            }
            if ($result_limit > $this->result_count) {
                $result_limit = $this->result_count;
            }
        } else {
            $result_limit = $this->result_count;
        }

        // 最終ページの計算
        $this->end_page_num = ceil($result_limit / $this->view_size);

        //総ページ数
        $all_page_num = ceil($result_limit / $view_size);

        $page_range_center = ceil($page_range / 2);

        if ($page_range > $all_page_num) {
            //表示ページ数より総ページ数が少ない場合は全部
            $this->page_range_start = 1;
            $this->page_range_end = $page_range;

        } else {
            //現在ページがページ中央より小さい場合
            if ($page_range_center >= $this->current_page_num) {
                $this->page_range_start = 1;
                $this->page_range_end = $page_range;
            } //
            elseif ($all_page_num <= ($this->current_page_num + $page_range_center - 1)) {
                $this->page_range_start = $all_page_num - $page_range + 1;
                $this->page_range_end = $all_page_num;

            } else {

                $this->page_range_start = $this->current_page_num - $page_range_center + 1;
                $this->page_range_end = $this->current_page_num + $page_range_center - 1;
            }
        }

        if ($this->page_range_end > $this->end_page_num) {
            $this->page_range_end = $this->end_page_num;
        }


        $this->prev_page_num = $this->current_page_num - 1;
        if ($this->prev_page_num < 1) {
            $this->prev_page_num = 0;
        }
        $this->next_page_num = $this->current_page_num + 1;
        if ($this->next_page_num > $this->end_page_num) {
            $this->next_page_num = 0;
        }

    }

    /**
     * @return bool
     */
    public function hasPrevPage()
    {
        return ($this->current_page_num > 1);
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return ($this->current_page_num < $this->end_page_num);
    }

    /**
     * @return bool
     */
    public function isCurrentPage($page_num)
    {
        return ($this->current_page_num == $page_num);
    }

    /**
     * @return bool
     */
    public function isLitterThanPageRange($page_num)
    {
        return ($this->page_range_start > $page_num);
    }

    /**
     * @return bool
     */
    public function isGreaterThanPageRange($page_num)
    {
        return ($this->page_range_end < $page_num);
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return ($this->current_page_num > 1);
    }

    /**
     * @return array
     */
    public function getPageRange()
    {
        return range($this->page_range_start, $this->page_range_end);
    }


    public function isValidatePage()
    {
        return (bool)$this->current_page_num;
    }
}

