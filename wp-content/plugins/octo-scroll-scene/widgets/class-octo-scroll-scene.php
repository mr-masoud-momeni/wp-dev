<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Octo Scroll Scene Elementor Widget
 */
class Octo_Scroll_Scene extends \Elementor\Widget_Base {


	/*
	|--------------------------------------------------------------------------
	| Widget Identity
	|--------------------------------------------------------------------------
	*/

	public function get_name(): string {
		return 'octo_scroll_scene';
	}


	public function get_title(): string {
		return esc_html__( 'Octo Scroll Scene', 'octo-scroll-scene' );
	}


	public function get_icon(): string {
		return 'eicon-scroll';
	}


	public function get_categories(): array {
		return array( 'basic' );
	}


	public function get_keywords(): array {
		return array(
			'scroll',
			'frames',
			'sequence',
			'canvas',
			'gsap',
			'scene',
			'octoopus',
		);
	}


	/*
	|--------------------------------------------------------------------------
	| Assets
	|--------------------------------------------------------------------------
	|
	| این دو متد به Elementor می‌گویند که این Widget
	| به JS و CSS مخصوص خودش نیاز دارد.
	|
	*/

	public function get_script_depends(): array {
		return array(
			'octo-scroll-scene',
		);
	}


	public function get_style_depends(): array {
		return array(
			'octo-scroll-scene',
		);
	}


	/*
	|--------------------------------------------------------------------------
	| Elementor Controls
	|--------------------------------------------------------------------------
	|
	| تمام تنظیماتی که در پنل Elementor می‌بینیم
	| از اینجا ساخته می‌شوند.
	|
	*/

	protected function register_controls(): void {


		/*
		|--------------------------------------------------------------------------
		| Frame Engine
		|--------------------------------------------------------------------------
		*/

		$this->start_controls_section(
			'section_frame_engine',
			array(
				'label' => esc_html__( 'Frame Engine', 'octo-scroll-scene' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);


		/*
		 * Frame Path
		 *
		 * مثال:
		 *
		 * /wp-content/uploads/sites/2/scroll-scenes/octoopus/frame_
		 *
		 * توجه:
		 * اینجا خود frame_ را هم می‌نویسیم.
		 */
		$this->add_control(
			'frame_path',
			array(
				'label'       => esc_html__( 'Frame Base Path', 'octo-scroll-scene' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => '/wp-content/uploads/sites/2/scroll-scenes/octoopus/frame_',
				'description' => esc_html__(
					'Path up to the filename prefix. Example: .../frame_',
					'octo-scroll-scene'
				),
			)
		);


		/*
		 * Frame Extension
		 */
		$this->add_control(
			'frame_extension',
			array(
				'label'   => esc_html__( 'Frame Extension', 'octo-scroll-scene' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'webp' => 'WebP',
					'avif' => 'AVIF',
					'jpg'  => 'JPG',
					'jpeg' => 'JPEG',
					'png'  => 'PNG',
				),
				'default' => 'webp',
			)
		);


		/*
		 * Frame Count
		 *
		 * مثلاً:
		 *
		 * 89
		 */
		$this->add_control(
			'frame_count',
			array(
				'label'   => esc_html__( 'Frame Count', 'octo-scroll-scene' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 5000,
				'step'    => 1,
				'default' => 89,
			)
		);


		/*
		 * Filename Digits
		 *
		 * برای:
		 *
		 * frame_0001.webp
		 *
		 * مقدار = 4
		 */
		$this->add_control(
			'frame_digits',
			array(
				'label'       => esc_html__( 'Filename Digits', 'octo-scroll-scene' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 8,
				'step'        => 1,
				'default'     => 4,
				'description' => esc_html__(
					'For frame_0001.webp use 4.',
					'octo-scroll-scene'
				),
			)
		);


		/*
		 * Start Frame
		 *
		 * فعلاً برای انعطاف بیشتر نگهش می‌داریم.
		 */
		$this->add_control(
			'start_frame',
			array(
				'label'   => esc_html__( 'Start Frame', 'octo-scroll-scene' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'step'    => 1,
				'default' => 1,
			)
		);


		/*
		 * Hero Height
		 *
		 * ارتفاع خود صحنه با مقدار Scroll Distance
		 * دو مفهوم جدا هستند.
		 */
		$this->add_control(
			'hero_height',
			array(
				'label'   => esc_html__( 'Hero Height', 'octo-scroll-scene' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'100vh'  => '100vh',
					'80vh'   => '80vh',
					'90vh'   => '90vh',
					'120vh'  => '120vh',
					'custom' => 'Custom',
				),
				'default' => '100vh',
			)
		);


		/*
		 * Custom Hero Height
		 *
		 * فقط وقتی Hero Height روی Custom باشد
		 * نمایش داده می‌شود.
		 */
		$this->add_control(
			'hero_height_custom',
			array(
				'label'      => esc_html__( 'Custom Height', 'octo-scroll-scene' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 300,
						'max' => 2000,
					),
					'vh' => array(
						'min' => 30,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'vh',
					'size' => 100,
				),
				'condition'  => array(
					'hero_height' => 'custom',
				),
			)
		);


		/*
		 * Scroll Distance
		 *
		 * مثلاً:
		 *
		 * Hero Height = 100vh
		 * Scroll Distance = 2500px
		 *
		 * یعنی Canvas در صفحه 100vh دیده می‌شود،
		 * اما انیمیشن در طول 2500px اسکرول اتفاق می‌افتد.
		 */
		$this->add_control(
			'scroll_distance',
			array(
				'label'      => esc_html__( 'Scroll Distance', 'octo-scroll-scene' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 500,
						'max' => 10000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2500,
				),
				'description' => esc_html__(
					'How much page scroll is used to play the sequence.',
					'octo-scroll-scene'
				),
			)
		);


		$this->end_controls_section();
		
		
		
		/*
		|--------------------------------------------------------------------------
		| Hero Content
		|--------------------------------------------------------------------------
		*/		
		
		$this->start_controls_section(
        	'section_hero_content',
        	array(
        		'label' => esc_html__( 'Hero Content', 'octo-scroll-scene' ),
        		'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        	)
        );
        
        $this->add_control(
        	'hero_title',
        	array(
        		'label'       => esc_html__( 'Title', 'octo-scroll-scene' ),
        		'type'        => \Elementor\Controls_Manager::TEXTAREA,
        		'rows'        => 2,
        		'default'     => esc_html__( 'زیبایی، وقتی ارزشمند است که اصالت داشته باشد.', 'octo-scroll-scene' ),
        		'placeholder' => esc_html__( 'Enter title', 'octo-scroll-scene' ),
        	)
        );
        
        $this->add_control(
        	'hero_subtitle',
        	array(
        		'label'       => esc_html__( 'Subtitle', 'octo-scroll-scene' ),
        		'type'        => \Elementor\Controls_Manager::TEXTAREA,
        		'rows'        => 3,
        		'default'     => esc_html__( 'طراحی شده برای لحظاتی که ماندگار می‌شوند.', 'octo-scroll-scene' ),
        		'placeholder' => esc_html__( 'Enter subtitle', 'octo-scroll-scene' ),
        	)
        );
        
        $this->add_control(
        	'hero_button_text',
        	array(
        		'label'       => esc_html__( 'Button Text', 'octo-scroll-scene' ),
        		'type'        => \Elementor\Controls_Manager::TEXT,
        		'default'     => esc_html__( 'کشف کالکشن', 'octo-scroll-scene' ),
        	)
        );
        
        $this->add_control(
        	'hero_button_url',
        	array(
        		'label'       => esc_html__( 'Button URL', 'octo-scroll-scene' ),
        		'type'        => \Elementor\Controls_Manager::URL,
        		'placeholder' => 'https://example.com',
        		'default'     => array(
        			'url' => '',
        		),
        	)
        );
        
        $this->add_control(
        	'desktop_position_heading',
        	array(
        		'label'     => esc_html__( 'Desktop Position', 'octo-scroll-scene' ),
        		'type'      => \Elementor\Controls_Manager::HEADING,
        		'separator' => 'before',
        	)
        );
        
        $this->add_control(
        	'desktop_position_x',
        	array(
        		'label'      => esc_html__( 'X Position', 'octo-scroll-scene' ),
        		'type'       => \Elementor\Controls_Manager::SLIDER,
        		'size_units' => array( '%' ),
        		'range'      => array(
        			'%' => array(
        				'min' => 0,
        				'max' => 100,
        				'step' => 1,
        			),
        		),
        		'default'    => array(
        			'unit' => '%',
        			'size' => 50,
        		),
        	)
        );
        
        $this->add_control(
        	'desktop_position_y',
        	array(
        		'label'      => esc_html__( 'Y Position', 'octo-scroll-scene' ),
        		'type'       => \Elementor\Controls_Manager::SLIDER,
        		'size_units' => array( '%' ),
        		'range'      => array(
        			'%' => array(
        				'min' => 0,
        				'max' => 100,
        				'step' => 1,
        			),
        		),
        		'default'    => array(
        			'unit' => '%',
        			'size' => 50,
        		),
        	)
        );
        
        $this->add_control(
        	'mobile_position_heading',
        	array(
        		'label'     => esc_html__( 'Mobile Position', 'octo-scroll-scene' ),
        		'type'      => \Elementor\Controls_Manager::HEADING,
        		'separator' => 'before',
        	)
        );
        
        $this->add_control(
        	'mobile_position_x',
        	array(
        		'label'      => esc_html__( 'X Position', 'octo-scroll-scene' ),
        		'type'       => \Elementor\Controls_Manager::SLIDER,
        		'size_units' => array( '%' ),
        		'range'      => array(
        			'%' => array(
        				'min' => 0,
        				'max' => 100,
        				'step' => 1,
        			),
        		),
        		'default'    => array(
        			'unit' => '%',
        			'size' => 50,
        		),
        	)
        );
        
        $this->add_control(
        	'mobile_position_y',
        	array(
        		'label'      => esc_html__( 'Y Position', 'octo-scroll-scene' ),
        		'type'       => \Elementor\Controls_Manager::SLIDER,
        		'size_units' => array( '%' ),
        		'range'      => array(
        			'%' => array(
        				'min' => 0,
        				'max' => 100,
        				'step' => 1,
        			),
        		),
        		'default'    => array(
        			'unit' => '%',
        			'size' => 50,
        		),
        	)
        );
        
        $this->end_controls_section();
	}


	/*
	|--------------------------------------------------------------------------
	| Render
	|--------------------------------------------------------------------------
	|
	| فعلاً فقط یک خروجی ساده می‌دهیم.
	| در مرحله بعد Canvas و ساختار واقعی Hero را اینجا می‌سازیم.
	|
	*/

    protected function render(): void {
    
    	$settings = $this->get_settings_for_display();
    
    	$hero_height = '100vh';
    
    	if (
    		! empty( $settings['hero_height'] ) &&
    		'custom' !== $settings['hero_height']
    	) {
    		$hero_height = $settings['hero_height'];
    	}
    
    	if (
    		'custom' === ( $settings['hero_height'] ?? '' ) &&
    		! empty( $settings['hero_height_custom']['size'] )
    	) {
    		$unit = ! empty( $settings['hero_height_custom']['unit'] )
    			? $settings['hero_height_custom']['unit']
    			: 'vh';
    
    		$hero_height =
    			$settings['hero_height_custom']['size'] . $unit;
    	}
    
    	$scroll_distance = 2500;
    
    	if ( ! empty( $settings['scroll_distance']['size'] ) ) {
    		$scroll_distance = (int) $settings['scroll_distance']['size'];
    	}
    
    	$config = array(
    		'frame_path'      => ! empty( $settings['frame_path'] )
    			? $settings['frame_path']
    			: '',
    
    		'frame_extension' => ! empty( $settings['frame_extension'] )
    			? $settings['frame_extension']
    			: 'webp',
    
    		'frame_count'     => ! empty( $settings['frame_count'] )
    			? (int) $settings['frame_count']
    			: 89,
    
    		'frame_digits'    => ! empty( $settings['frame_digits'] )
    			? (int) $settings['frame_digits']
    			: 4,
    
    		'start_frame'     => ! empty( $settings['start_frame'] )
    			? (int) $settings['start_frame']
    			: 1,
    
    		'scroll_distance' => $scroll_distance,
    
    		'canvas_width'    => 1920,
    
    		'canvas_height'   => 1080,
    	);
    
    	$config_json = wp_json_encode( $config );
    
    	$desktop_x = ! empty( $settings['desktop_position_x']['size'] )
    		? $settings['desktop_position_x']['size']
    		: 50;
    
    	$desktop_y = ! empty( $settings['desktop_position_y']['size'] )
    		? $settings['desktop_position_y']['size']
    		: 50;
    
    	$mobile_x = ! empty( $settings['mobile_position_x']['size'] )
    		? $settings['mobile_position_x']['size']
    		: 50;
    
    	$mobile_y = ! empty( $settings['mobile_position_y']['size'] )
    		? $settings['mobile_position_y']['size']
    		: 50;
    
    	$button_url = ! empty( $settings['hero_button_url']['url'] )
    		? $settings['hero_button_url']['url']
    		: '#';
    		
    		
    		
    		
    		
    		
    		
    	$frame_path = ! empty( $settings['frame_path'] )
        	? $settings['frame_path']
        	: '';
        
        $frame_extension = ! empty( $settings['frame_extension'] )
        	? $settings['frame_extension']
        	: 'webp';
        
        $frame_digits = ! empty( $settings['frame_digits'] )
        	? (int) $settings['frame_digits']
        	: 4;
        
        $start_frame = ! empty( $settings['start_frame'] )
        	? (int) $settings['start_frame']
        	: 1;
        
        $first_frame = str_pad(
        	(string) $start_frame,
        	$frame_digits,
        	'0',
        	STR_PAD_LEFT
        );
        
        $poster_url = $frame_path . $first_frame . '.' . $frame_extension;
    
    	?>
    
    	<div
        	class="octo-scroll-scene"
        	style="
        		--octo-hero-height: <?php echo esc_attr( $hero_height ); ?>;
        		--octo-content-x: <?php echo esc_attr( $desktop_x ); ?>%;
        		--octo-content-y: <?php echo esc_attr( $desktop_y ); ?>%;
        		--octo-content-mobile-x: <?php echo esc_attr( $mobile_x ); ?>%;
        		--octo-content-mobile-y: <?php echo esc_attr( $mobile_y ); ?>%;
        	"
        	data-config="<?php echo esc_attr( $config_json ); ?>"
        >
    
    		<div class="octo-stage">
                <img
            		class="octo-poster"
            		src="<?php echo esc_url( $poster_url ); ?>"
            		width="1920"
            		height="1080"
            		alt=""
            		fetchpriority="high"
            		decoding="async"
            	>
    			<canvas
    				class="octo-canvas"
    				width="1920"
    				height="1080"
    			></canvas>
    			<div class="octo-decorative-line"></div>
    			<div class="octo-canvas-overlay"></div>
    
    			<div class="octo-hero-content">
    
    				<?php if ( ! empty( $settings['hero_title'] ) ) : ?>
    
    					<h1 class="octo-hero-title">
    						<?php echo nl2br( esc_html( $settings['hero_title'] ) ); ?>
    					</h1>
    
    				<?php endif; ?>
    
    				<?php if ( ! empty( $settings['hero_subtitle'] ) ) : ?>
    
    					<div class="octo-hero-subtitle">
    						<?php echo nl2br( esc_html( $settings['hero_subtitle'] ) ); ?>
    					</div>
    
    				<?php endif; ?>
    
    				<?php if ( ! empty( $settings['hero_button_text'] ) ) : ?>
    
    					<a
    						class="octo-hero-button"
    						href="<?php echo esc_url( $button_url ); ?>"
    					>
    						<?php echo esc_html( $settings['hero_button_text'] ); ?>
    					</a>
    
    				<?php endif; ?>
    
    			</div>
    
    		</div>
    
    	</div>
    
    	<?php
    }
}