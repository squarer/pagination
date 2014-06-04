<?php
class pagination{
    public $result;
    public $limited_result;
    public $page_size;
    public $current_page;

    function __construct($result, $page_size){
        $this->page_size = $page_size;
        $this->result = $result;
    }

    function get_result(){
        $this->current_page = empty($_GET['page']) || $_GET['page'] < 1 ? 1 : $_GET['page'];
        $row_offset = ($this->current_page - 1) * $this->page_size;
        $counter = 0;
        while($row = $this->result->fetch_assoc()){
            if($counter >= $row_offset && $counter < $row_offset + $this->page_size)
                $this->limited_result[] = $row;
            $counter += 1;
        }

        return $this->limited_result;
    }

    function render($pagination_number){
        $last_page = ceil($this->result->num_rows/$this->page_size);
        $prev_page = $this->current_page <= 1 ? 1 : $this->current_page - 1;
        $next_page = $this->current_page >= $last_page ? $last_page : $this->current_page + 1;
        $lowerbound_page = $this->current_page - floor($pagination_number/2) >= 1 ? $this->current_page - floor($pagination_number/2) : 1;
        $upperbound_page = $lowerbound_page + $pagination_number;
        if($upperbound_page > $last_page){
            $lowerbound_page -= $upperbound_page - $last_page;
            $lowerbound_page = $lowerbound_page >= 1 ? $lowerbound_page : 1;
            $upperbound_page = $last_page;
        }

        echo "<style type='text/css'>";
        echo "ul{display: inline-block;}";
        echo "li{display: inline;}";
        echo ".pagination a{padding: 5px;}";
        echo "a.active{text-decoration: none;cursor: pointer;}";
        echo "a.disabled{text-decoration: none;}";
        echo "</style>";

        echo "<div class='pagination'>";
        echo "<ul>";
        echo "<li><a href='?page=1'>First</a></li>";
        if($this->current_page == 1)
            echo "<li><a class='disabled' href='#'>← Previous</a></li>";
        else
            echo "<li><a href='?page=$prev_page'>← Previous</a></li>";

        for ($i=$lowerbound_page; $i < $this->current_page; $i++) { 
            echo "<li><a href='?page=$i'>$i</a></li>";
        }

        echo "<li><a class='active' href='#'>$this->current_page</a></li>";

        for ($i=$this->current_page+1; $i <= $upperbound_page; $i++) { 
            echo "<li><a href='?page=$i'>$i</a></li>";
        }

        if($this->current_page == $last_page)
            echo "<li><a class='disabled' href='#'>Next →</a></li>";
        else
            echo "<li><a href='?page=$next_page'>Next →</a></li>";

        echo "<li><a href='?page=$last_page'>Last</a></li>";
        echo "</ul>";
        echo "</div>";
    }

    function filter($key){
        return ($key >= $this->row_offset && $key <= $this->row_offset + $this->page_size);
    }

}

?>