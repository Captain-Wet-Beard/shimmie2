<?php

class CustomIndexTheme extends IndexTheme
{
    /**
     * #param Image[] $images
     */
    public function display_page(Page $page, array $images)
    {
        global $config;

        if (count($this->search_terms) == 0) {
            $query = null;
            $page_title = $config->get_string(SetupConfig::TITLE);
        } else {
            $search_string = implode(' ', $this->search_terms);
            $query = url_escape($search_string);
            $page_title = html_escape($search_string);
        }

        $nav = $this->build_navigation($this->page_number, $this->total_pages, $this->search_terms);
        $page->set_title($page_title);
        $page->set_heading($page_title);
        $page->add_block(new Block("Search", $nav, "left", 0));
        if (count($images) > 0) {
            if ($query) {
                $page->add_block(new Block("Images", $this->build_table($images, "search=$query"), "main", 10));
                $this->display_paginator($page, "post/list/$query", null, $this->page_number, $this->total_pages);
            } else {
                $page->add_block(new Block("Images", $this->build_table($images, null), "main", 10));
                $this->display_paginator($page, "post/list", null, $this->page_number, $this->total_pages);
            }
        } else {
            $page->add_block(new Block("No Images Found", "No images were found to match the search criteria"));
        }
    }

    /**
     * #param string[] $search_terms
     */
    protected function build_navigation(int $page_number, int $total_pages, array $search_terms): string
    {
        $h_search_string = count($search_terms) == 0 ? "" : html_escape(implode(" ", $search_terms));
        $h_search_link = make_link();
        $h_search = "
			<p><form action='$h_search_link' method='GET'>
				<input name='search' type='text' value='$h_search_string' class='autocomplete_tags' placeholder=''  style='width:75%'/>
				<input type='submit' value='Go' style='width:20%'>
				<input type='hidden' name='q' value='/post/list'>
			</form>
			<div id='search_completions'></div>";

        return $h_search;
    }

    /**
     * #param Image[] $images
     */
    protected function build_table(array $images, ?string $query): string
    {
        $h_query = html_escape($query);
        $table = "<div class='shm-image-list' data-query='$h_query'>";
        foreach ($images as $image) {
            $table .= "\t<span class=\"thumb\">" . $this->build_thumb_html($image) . "</span>\n";
        }
        $table .= "</div>";
        return $table;
    }
}
