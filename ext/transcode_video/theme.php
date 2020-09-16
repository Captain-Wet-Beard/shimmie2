<?php declare(strict_types=1);

class TranscodeVideoTheme extends Themelet
{
    /*
     * Display a link to resize an image
     */
    public function get_transcode_html(Image $image, array $options)
    {
        $html = "
			".make_form(
            make_link("transcode_video/{$image->id}"),
            'POST',
            false,
            "",
            //"return transcodeSubmit()"
        )."
                <input type='hidden' name='image_id' value='{$image->id}'>
                <input type='hidden' name='codec' value='{$image->video_codec}'>
                ".$this->get_transcode_picker_html($options)."
				<br><input id='transcodebutton' type='submit' value='Transcode Video'>
			</form>
		";

        return $html;
    }

    public function get_transcode_picker_html(array $options)
    {
        $html = "<select id='transcode_format'  name='transcode_format' required='required' >";
        foreach ($options as $display=>$value) {
            $html .= "<option value='$value'>$display</option>";
        }

        return $html."</select>";
    }

    public function display_transcode_error(Page $page, string $title, string $message)
    {
        $page->set_title("Transcode Video");
        $page->set_heading("Transcode Video");
        $page->add_block(new NavBlock());
        $page->add_block(new Block($title, $message));
    }
}
