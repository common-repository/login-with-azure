<?php
/**
 * Power BI settings tab file
 *
 * @package    power-bi
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Class for displaying the settings tab under Power BI.
 */
class MOAzure_PBI_Settings {

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
	public static function get_pbi_settings_obj() {
		if ( ! isset( self::$instance ) ) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Function to display the settings tab of Power BI.
	 *
	 * @return void
	 */
    public function moazure_pbi_settings() {
        $this->moazure_pbi_rls();
		$this->moazure_pbi_embed_options();
		$this->moazure_pbi_embedded_settings();
    }

	/**
	 * Function to display the RLS section in settings tab.
	 *
	 * @return void
	 */
	public function moazure_pbi_rls() {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Row Level Security
				</h3>
			</div>
			<hr class='mo-divider'>
			<div class="mo_settings_table moazure_configure_table">
				<div class="moazure_contact_heading moazure-flex" style="gap: 2rem;">
					<strong class="mo_strong">Configure Row Level Security : </strong>
					<label class="moazure_switch" style="float: left;">
						<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
						<span class="moazure_slider round"></span>
					</label>
				</div>
				<p class="moazure_desc" style="font-style: normal;">
					Lets you apply restrictions on data row access in your application. For example, limit user access to rows relevant to their department, or restrict customer access to only the data relevant to their company.
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to display the Embed Options section in settings tab
	 *
	 * @return void
	 */
	public function moazure_pbi_embed_options() {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Embed Options
				</h3>
			</div>
			<hr class='mo-divider'>
			<div class="mo_settings_table moazure_configure_table">
                <fieldset>
					<strong class="mo_strong moazure_sub_head">Resource Embed Type</strong>
                    <p class="moazure_desc">[Here you can choose the type in which you want to embed the Power BI Resource]</p>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option1" checked disabled> Report
                        </label>
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option2" disabled> Dashboard
                        </label>
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option2" disabled> Tile
                        </label><br>
                    </div>
                    <div class="moazure-flex">
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option1" disabled> Q&A
                        </label>
                        <label style="width: 150px;">
                            <input type="radio" name="topic1" value="option2" disabled> Report Visual
                        </label>
                    </div>
                </fieldset><br>

                <fieldset>
					<strong class="mo_strong moazure_sub_head">Resource Embed Mode</strong>
                    <p class="moazure_desc">[Here you can choose the mode in which you would like to embed the Power BI Resource]</p>
                    <div class="moazure-flex">
                        <label style="width:150px;">
                            <input type="radio" name="topic2" value="option1" checked disabled> View Mode
                        </label>
                        <label style="width: 150px;">
                            <input type="radio" name="topic2" value="option2" disabled> Edit Mode
                        </label>
                        <label style="width: 150px;">
                            <input type="radio" name="topic2" value="option2" disabled> Create Mode
                        </label>
                    </div>
                </fieldset><br>

                <button class="button button-primary button-large mo_disabled_btn" >Save Settings</button>
            </div>
			<br>
		</div>
		<?php
	}

	/**
	 * Function to display the embedded settings section in settings tab.
	 *
	 * @return void
	 */
	public function moazure_pbi_embedded_settings() {
		?>
		<div class="moazure_table_layout moazure_outer_div" id="gen_sc_div" style="display: <?php echo ! empty( $shortcodes ) ? 'none' : 'block'; ?>">
			<div>
				<h3 class='mo_app_heading moazure_configure_heading' style='font-size:20px'>
					Settings for Embedded Resource
				</h3>
			</div>
			<hr class='mo-divider'>

			<div class="mo_settings_table moazure_configure_table" style="padding-bottom: 0;">
				<div class="moazure_contact_heading moazure-flex">
					<strong class="mo_strong" style="width: 33%;">Filter Pane : </strong>
					<label class="moazure_switch" style="float: left;">
						<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
						<span class="moazure_slider round"></span>
					</label>
				</div><br>
	
				<div class="moazure_contact_heading moazure-flex">
					<strong class="mo_strong" style="width: 33%;">Page Navigation : </strong>
					<label class="moazure_switch" style="float: left;">
						<input class="mo_input_checkbox" id="toggleSwitch" type="checkbox" name="moazure_show_on_login_page" disabled />
						<span class="moazure_slider round"></span>
					</label>
				</div>
			</div>

            <table class="mo_settings_table moazure_configure_table">
                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Language : </strong>
						<p class="moazure_desc">This lets you modify the language of the embedded resource</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the language" disabled />
                    </td>
                </tr>

                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Format Locale : </strong>
						<p class="moazure_desc">This lets you modify the format of the embedded resource</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the format" disabled />
                    </td>
                </tr>
				
                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Mobile Breakpoint (in px) : </strong>
						<p class="moazure_desc">This is the breakpoint to enable the mobile view accordingly</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the breakpoint" disabled />
                    </td>
                </tr>
				
                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Mobile Height (in px) : </strong>
						<p class="moazure_desc">This is the height of the embedded resource</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the height" disabled />
                    </td>
                </tr>
				
                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Mobile Width (in px) : </strong>
						<p class="moazure_desc">This is the width of the embedded resource</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the width" disabled />
                    </td>
                </tr>

                <tr class="moazure_configure_table_rows">
					<td class="moazure_contact_heading td_entra_app">
						<strong class="mo_strong">Page Name : </strong>
						<p class="moazure_desc">This lets you decide the name of the page</p>
					</td>
                    <td class="moazure_contact_heading td_entra_app">
                        <input type="text" name="topic6" value="Enter the page name" disabled />
                    </td>
                </tr>

				<tr>
					<td>
						<button class="button button-primary button-large mo_disabled_btn" >Save Settings</button>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}