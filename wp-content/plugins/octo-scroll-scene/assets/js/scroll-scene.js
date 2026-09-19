(() => {
	'use strict';

	class OctoScrollScene {

		constructor(root) {
			this.root = root;
			this.stage = root.querySelector('.octo-stage');
			this.canvas = root.querySelector('.octo-canvas');
			this.ctx = this.canvas ? this.canvas.getContext('2d') : null;

			this.config = this.getConfig();

			this.frames = [];
			this.loadedFrames = 0;
			this.currentFrame = -1;

			this.playhead = {
				frame: 0
			};

			this.scrollTrigger = null;

			this.init();
		}

		getConfig() {

			const config = this.root.dataset.config;

			if (!config) {
				return {};
			}

			try {
				return JSON.parse(config);
			} catch (error) {
				console.error(
					'Octo Scroll Scene: Invalid configuration.',
					error
				);

				return {};
			}
		}

		init() {

			if (
				!this.stage ||
				!this.canvas ||
				!this.ctx
			) {
				return;
			}

			if (
				typeof gsap === 'undefined' ||
				typeof ScrollTrigger === 'undefined'
			) {
				console.error(
					'Octo Scroll Scene: GSAP or ScrollTrigger is missing.'
				);

				return;
			}

			gsap.registerPlugin(ScrollTrigger);

			this.prepareCanvas();
			this.loadFrames();
		}

		prepareCanvas() {

        	const width = parseInt(
        		this.config.canvas_width || 1920,
        		10
        	);
        
        	const height = parseInt(
        		this.config.canvas_height || 1080,
        		10
        	);
        
        	this.canvas.width = width;
        	this.canvas.height = height;
        
        	const resizeCanvas = () => {
        
        		const stageWidth = this.stage.clientWidth;
        		const stageHeight = this.stage.clientHeight;
        
        		const imageRatio = width / height;
        		const stageRatio = stageWidth / stageHeight;
        
        		let displayWidth;
        		let displayHeight;
        
        		/*
        		 * Cover:
        		 * نسبت 16:9 حفظ می‌شود.
        		 * تصویر آنقدر بزرگ می‌شود که کل Stage را بپوشاند.
        		 */
        
        		if (stageRatio > imageRatio) {
        
        			// صفحه عریض‌تر از تصویر است
        			displayWidth = stageWidth;
        			displayHeight = stageWidth / imageRatio;
        
        		} else {
        
        			// صفحه بلندتر از تصویر است
        			displayHeight = stageHeight;
        			displayWidth = stageHeight * imageRatio;
        		}
        

        	};
        
        	resizeCanvas();
        
        	window.addEventListener(
        		'resize',
        		resizeCanvas,
        		{ passive: true }
        	);
        }

		getFrameUrl(index) {

			const frameNumber = String(index).padStart(
				parseInt(this.config.frame_digits || 4, 10),
				'0'
			);

			return (
				this.config.frame_path +
				frameNumber +
				'.' +
				this.config.frame_extension
			);
		}

		loadFrames() {

			const frameCount = parseInt(
				this.config.frame_count || 0,
				10
			);

			if (!frameCount) {
				console.error(
					'Octo Scroll Scene: Frame count is missing.'
				);

				return;
			}

			const startFrame = parseInt(
				this.config.start_frame || 1,
				10
			);

			this.frames = new Array(frameCount);

			let firstFrameLoaded = false;

			for (let i = 0; i < frameCount; i++) {

				const frameNumber = startFrame + i;

				const image = new Image();

				image.decoding = 'async';

				image.onload = () => {

					this.loadedFrames++;

					if (!firstFrameLoaded) {

						firstFrameLoaded = true;

						this.drawFrame(0);
						this.createScrollTrigger();
					}
				};

				image.onerror = () => {

					console.warn(
						'Octo Scroll Scene: Failed to load frame:',
						this.getFrameUrl(frameNumber)
					);
				};

				image.src = this.getFrameUrl(frameNumber);

				this.frames[i] = image;
			}
		}

		drawFrame(frameIndex) {

			if (!this.frames.length) {
				return;
			}

			frameIndex = Math.max(
				0,
				Math.min(
					frameIndex,
					this.frames.length - 1
				)
			);

			const image = this.frames[frameIndex];

			if (
				!image ||
				!image.complete ||
				!image.naturalWidth
			) {
				return;
			}

			if (this.currentFrame === frameIndex) {
				return;
			}

			this.currentFrame = frameIndex;

			const canvasWidth = this.canvas.width;
			const canvasHeight = this.canvas.height;

			const imageWidth = image.naturalWidth;
			const imageHeight = image.naturalHeight;

			const scale = Math.max(
				canvasWidth / imageWidth,
				canvasHeight / imageHeight
			);

			const drawWidth = imageWidth * scale;
			const drawHeight = imageHeight * scale;

			const offsetX =
				(canvasWidth - drawWidth) / 2;

			const offsetY =
				(canvasHeight - drawHeight) / 2;

			this.ctx.clearRect(
				0,
				0,
				canvasWidth,
				canvasHeight
			);

			this.ctx.drawImage(
				image,
				offsetX,
				offsetY,
				drawWidth,
				drawHeight
			);
		}

		createScrollTrigger() {

			const scrollDistance = parseInt(
				this.config.scroll_distance || 2500,
				10
			);

			const frameCount = this.frames.length;

			this.scrollTrigger = ScrollTrigger.create({

				trigger: this.root,

				start: 'top top',

				end: `+=${scrollDistance}`,

				pin: this.stage,

				scrub: true,

				anticipatePin: 1,

				onUpdate: (self) => {

					const frame = Math.round(
						self.progress * (frameCount - 1)
					);

					this.playhead.frame = frame;

					this.drawFrame(frame);
				}
			});
		}
	}


	function initOctoScrollScenes() {

		const roots = document.querySelectorAll(
			'.octo-scroll-scene'
		);

		if (!roots.length) {
			return;
		}

		roots.forEach((root) => {

			if (root.dataset.octoInitialized === 'true') {
				return;
			}

			root.dataset.octoInitialized = 'true';

			new OctoScrollScene(root);
		});
	}


	if (document.readyState === 'loading') {

		document.addEventListener(
			'DOMContentLoaded',
			initOctoScrollScenes
		);

	} else {

		initOctoScrollScenes();
	}


	/*
	 * Elementor frontend support
	 */
	if (
		typeof jQuery !== 'undefined' &&
		typeof elementorFrontend !== 'undefined'
	) {

		jQuery(window).on(
			'elementor/frontend/init',
			() => {

				elementorFrontend.hooks.addAction(
					'frontend/element_ready/octo_scroll_scene.default',
					($element) => {

						const root =
							$element[0].querySelector(
								'.octo-scroll-scene'
							);

						if (
							root &&
							root.dataset.octoInitialized !== 'true'
						) {
							root.dataset.octoInitialized = 'true';

							new OctoScrollScene(root);
						}
					}
				);
			}
		);
	}

})();