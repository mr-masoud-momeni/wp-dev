<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Default Settings
|--------------------------------------------------------------------------
*/

function octo_banner_slider_default_settings()
{
    return [

        'autoplay'      => 1,

        'delay'         => 5000,

        'duration'      => 600,

        'effect'        => 'slide',

        'loop'          => 1,

        'pause'         => 1,

        'navigation'    => 1,

        'pagination'    => 1,

    ];
}


function octo_banner_layout_default()
{
    return 'slider';
}


/*
|--------------------------------------------------------------------------
| Add Category Fields
|--------------------------------------------------------------------------
*/

add_action(
    'banner_category_add_form_fields',
    'octo_banner_category_add_fields'
);


function octo_banner_category_add_fields()
{

    $settings = octo_banner_slider_default_settings();

?>

<div class="form-field">

    <label>
        Banner Layout
    </label>


    <select
        name="banner_layout"
        id="octo_banner_layout">


        <option value="slider">
            Slider
        </option>


        <option value="grid">
            Grid
        </option>


    </select>


</div>


<div id="octo-slider-settings">


<h2>
    Slider Settings
</h2>


<div class="form-field">

<label>

<input

type="checkbox"

name="slider_settings[autoplay]"

value="1"

checked>

Autoplay

</label>


</div>



<div class="form-field">


<label>
Delay (ms)
</label>


<input

type="number"

name="slider_settings[delay]"

value="<?php echo esc_attr($settings['delay']); ?>">


</div>




<div class="form-field">


<label>
Transition Duration (ms)
</label>


<input

type="number"

name="slider_settings[duration]"

value="<?php echo esc_attr($settings['duration']); ?>">


</div>




<div class="form-field">


<label>
Effect
</label>


<select name="slider_settings[effect]">


<option value="slide">
Slide
</option>


<option value="fade">
Fade
</option>


</select>


</div>




<div class="form-field">

<label>

<input

type="checkbox"

name="slider_settings[loop]"

value="1"

checked>

Infinite Loop

</label>


</div>




<div class="form-field">

<label>

<input

type="checkbox"

name="slider_settings[pause]"

value="1"

checked>

Pause On Hover

</label>


</div>




<div class="form-field">

<label>

<input

type="checkbox"

name="slider_settings[navigation]"

value="1"

checked>

Show Navigation

</label>


</div>




<div class="form-field">

<label>

<input

type="checkbox"

name="slider_settings[pagination]"

value="1"

checked>

Show Pagination

</label>


</div>


</div>


<?php

}




/*
|--------------------------------------------------------------------------
| Edit Category Fields
|--------------------------------------------------------------------------
*/


add_action(
    'banner_category_edit_form_fields',
    'octo_banner_category_edit_fields'
);


function octo_banner_category_edit_fields($term)
{


$layout = get_term_meta(

    $term->term_id,

    '_octo_banner_layout',

    true

);


$layout = $layout ?: 'slider';



$settings = get_term_meta(

    $term->term_id,

    '_octo_slider_settings',

    true

);



$settings = wp_parse_args(

    $settings,

    octo_banner_slider_default_settings()

);


?>


<tr>

<th>
Banner Layout
</th>


<td>


<select

name="banner_layout"

id="octo_banner_layout">


<option

value="slider"

<?php selected($layout,'slider'); ?>>

Slider

</option>



<option

value="grid"

<?php selected($layout,'grid'); ?>>

Grid

</option>



</select>


</td>


</tr>



<tbody id="octo-slider-settings">


<tr>

<th colspan="2">

<h2>
Slider Settings
</h2>


</th>


</tr>



<tr>

<th>
Autoplay
</th>


<td>


<input

type="checkbox"

name="slider_settings[autoplay]"

value="1"

<?php checked($settings['autoplay']); ?>>


</td>


</tr>




<tr>

<th>
Delay
</th>


<td>


<input

type="number"

name="slider_settings[delay]"

value="<?php echo esc_attr($settings['delay']); ?>">


</td>


</tr>




<tr>

<th>
Transition Duration
</th>


<td>


<input

type="number"

name="slider_settings[duration]"

value="<?php echo esc_attr($settings['duration']); ?>">


</td>


</tr>




<tr>

<th>
Effect
</th>


<td>


<select

name="slider_settings[effect]">


<option

value="slide"

<?php selected($settings['effect'],'slide'); ?>>

Slide

</option>


<option

value="fade"

<?php selected($settings['effect'],'fade'); ?>>

Fade

</option>


</select>


</td>


</tr>




<tr>

<th>
Infinite Loop
</th>


<td>


<input

type="checkbox"

name="slider_settings[loop]"

value="1"

<?php checked($settings['loop']); ?>>


</td>


</tr>




<tr>

<th>
Pause On Hover
</th>


<td>


<input

type="checkbox"

name="slider_settings[pause]"

value="1"

<?php checked($settings['pause']); ?>>


</td>


</tr>




<tr>

<th>
Navigation
</th>


<td>


<input

type="checkbox"

name="slider_settings[navigation]"

value="1"

<?php checked($settings['navigation']); ?>>


</td>


</tr>




<tr>

<th>
Pagination
</th>


<td>


<input

type="checkbox"

name="slider_settings[pagination]"

value="1"

<?php checked($settings['pagination']); ?>>


</td>


</tr>



</tbody>


<?php

}




/*
|--------------------------------------------------------------------------
| Save Settings
|--------------------------------------------------------------------------
*/


add_action(
    'created_banner_category',
    'octo_save_banner_category_settings'
);


add_action(
    'edited_banner_category',
    'octo_save_banner_category_settings'
);



function octo_save_banner_category_settings($term_id)
{


$layout = $_POST['banner_layout'] ?? 'slider';



if(
    !in_array(
        $layout,
        [
            'slider',
            'grid'
        ],
        true
    )
){

    $layout = 'slider';

}



update_term_meta(

    $term_id,

    '_octo_banner_layout',

    $layout

);





$defaults = octo_banner_slider_default_settings();


$input = $_POST['slider_settings'] ?? [];



$settings = [


'autoplay' => isset($input['autoplay']) ? 1 : 0,


'delay' => absint(

    $input['delay'] ?? $defaults['delay']

),



'duration' => absint(

    $input['duration'] ?? $defaults['duration']

),



'effect' => in_array(

    $input['effect'] ?? 'slide',

    [
        'slide',
        'fade'
    ],

    true

)

?

$input['effect']

:

'slide',



'loop' => isset($input['loop']) ? 1 : 0,


'pause' => isset($input['pause']) ? 1 : 0,


'navigation' => isset($input['navigation']) ? 1 : 0,


'pagination' => isset($input['pagination']) ? 1 : 0,


];




update_term_meta(

    $term_id,

    '_octo_slider_settings',

    $settings

);


}