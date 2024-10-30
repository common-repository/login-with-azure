<?php
/**
 * Sharepoint Advanced Settings tab file.
 *
 * @package    sharepoint
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Function to display the Embed View tab under Sharepoint.
 */
class MOAzure_SPS_Embed_View {

    /**
	 * Object variable.
	 *
	 * @var object variable to instantiate the class.
	 */
	private static $instance;

	/**
	 * Localized data variable.
	 *
	 * @var array variable to pass data to javascript.
	 */
	public $localized_data;

	/**
	 * Function to get the object of the class
	 *
	 * @return object
	 */
	public static function get_sps_embed_view_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

    /**
     * Function to display the embed view settings tab under Sharepoint.
     *
     * @return void
     */
    public function moazure_sps_embed_view_page() {
        ?>

		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
            <div>
                <h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
                    Add Shortcode
                </h3>
                <p class="moazure_desc" style="font-style: normal;">
                    Provide the following feilds to generate a shortcode for embedding Sharepoint Library as per the desired view.
                </p>
            </div>
			<hr class='mo-divider'>
            <div class="mo_settings_table moazure_configure_table">
                <!-- Topic 1: Radio Buttons -->
                <fieldset>
                    <h3>Default View</h3>
                    <p class="moazure_desc">[This allows you to change the layout of the embedded Sharepoint Library]</p>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option1" checked disabled> List View
                        </label><br>
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option2" disabled> Grid View
                        </label>
                    </div>
                </fieldset><br>

                <!-- Topic 2: Radio Buttons -->
                <fieldset>
                    <h3>Preview File Options</h3>
                    <p class="moazure_desc">[This allows you to modify the preview option for embedded Sharepoint Library documents]</p>
                    <div class="moazure-flex">
                        <label style="width:150px;">
                            <input type="radio" name="topic2" value="option1" checked disabled> Pop Up
                        </label><br>
                        <label style="width: 150px;">
                            <input type="radio" name="topic2" value="option2" disabled> New Tab
                        </label>
                    </div>
                </fieldset><br>

                <!-- Topic 3: Radio Buttons -->
                <fieldset>
                    <h3>File List View</h3>
                    <p class="moazure_desc">[This allows you to decide how you want to show the file list view in the Sharepoint Library]</p>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="radio" name="topic3" value="option1" disabled> Expanded View
                        </label><br>
                        <label style="width: 150px;">
                            <input type="radio" name="topic3" value="option2" checked disabled> Compact View
                        </label>
                    </div>
                </fieldset><br>

                <!-- Topic 4: Checkboxes -->
                <fieldset>
                    <h3>Elements to show</h3>
                    <p class="moazure_desc">[This lets you choose the elements which you want to show in the embedded Sharepoint Library]</p>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic4[]" value="checkbox1" checked disabled> Breadcrumbs
                        </label><br>
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic4[]" value="checkbox2" disabled> Toggle View
                        </label><br>
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic4[]" value="checkbox3" checked disabled> Search Bar
                        </label><br>
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic4[]" value="checkbox4" disabled> Upload Button
                        </label>
                    </div>
                </fieldset><br>

                <!-- Topic 5: Checkboxes -->
                <fieldset>
                    <h3>Table Columns</h3>
                    <p class="moazure_desc">[This lets you choose the columns of Sharepoint table which you want to show in the embedded Sharepoint Library]</p>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic5[]" value="checkbox1" disabled> Name
                        </label><br>
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic5[]" value="checkbox2" checked disabled> Last Modified Date
                        </label><br>
                        <label style="width: 150px;">
                            <input type="checkbox" name="topic5[]" value="checkbox3" disabled> Size
                        </label>
                    </div>
                </fieldset><br>

                <!-- Topic 6: Input Field -->
                <fieldset class="moazure-flex" style="gap: 2rem;">
                    <h3>Shortcode Width (in percent) : </h3>
                    <label>
                        <input type="text" name="topic6" value="Enter the shortcode width" disabled style="width: 300px;">
                    </label>
                </fieldset><br>

                <button class="button button-primary button-large mo_disabled_btn" >Generate Shortcode</button>
            </div>
		</div>

        <?php
    }
}