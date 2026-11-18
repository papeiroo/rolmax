<?php
/** Wspólna klasa widżetów sekcji Rolmax (Elementor). */
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class RX_Section_Widget extends \Elementor\Widget_Base {
	protected $rx = '';
	protected function rx_def() { $S = rx_sections(); return $S[ $this->rx ]; }
	public function get_name() { return 'rx_' . $this->rx; }
	public function get_title() { $d = $this->rx_def(); return $d['title']; }
	public function get_icon() { $d = $this->rx_def(); return $d['icon']; }
	public function get_categories() { return array( 'rolmax' ); }
	public function get_keywords() { return array( 'rolmax', $this->rx ); }

	private function rx_control( $obj, $key, $f, $default ) {
		$label = $f[0]; $type = $f[1];
		$map   = array(
			'text'     => \Elementor\Controls_Manager::TEXT,
			'url'      => \Elementor\Controls_Manager::TEXT,
			'textarea' => \Elementor\Controls_Manager::TEXTAREA,
			'switcher' => \Elementor\Controls_Manager::SWITCHER,
			'media'    => \Elementor\Controls_Manager::MEDIA,
		);
		$args = array( 'label' => $label, 'type' => $map[ $type ], 'label_block' => true );
		if ( $type === 'media' ) {
			$args['default'] = array( 'url' => rx_asset( $default ) );
		} elseif ( $type === 'switcher' ) {
			$args['default'] = $default; $args['return_value'] = 'yes'; $args['label_block'] = false;
		} else {
			$args['default'] = $default;
			if ( $type === 'textarea' ) $args['rows'] = 4;
		}
		if ( $type === 'url' ) $args['description'] = 'Adres strony albo kotwica, np. #oferta, #usluga-serwis, #maszyna-t5s.';
		$obj->add_control( $key, $args );
	}

	protected function register_controls() {
		$d = $this->rx_def();
		$this->start_controls_section( 'rx_content', array( 'label' => 'Treść', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ) );
		foreach ( $d['fields'] as $key => $f ) {
			if ( $f[1] === 'repeater' ) {
				$rep = new \Elementor\Repeater();
				foreach ( $f[3] as $sk => $sf ) $this->rx_control( $rep, $sk, $sf, '' );
				$this->add_control( $key, array(
					'label'       => $f[0],
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $rep->get_controls(),
					'default'     => $f[2],
					'title_field' => '{{{ ' . $f[4] . ' }}}',
				) );
			} else {
				$this->rx_control( $this, $key, $f, $f[2] );
			}
		}
		if ( in_array( $this->rx, array( 'kategorie', 'uslugi', 'nowosci', 'blog', 'aktualnosci', 'galeria', 'oddzialy', 'oferta' ), true ) ) {
			$this->add_control( 'rx_note', array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => '<small>Kafelki w tej sekcji (maszyny, usługi, wpisy, zdjęcia, oddziały) wypełniają się automatycznie z danych motywu Rolmax.</small>',
			) );
		}
		$this->end_controls_section();
	}

	protected function render() {
		rx_section( $this->rx, $this->get_settings_for_display() );
	}
}

class RX_W_hero extends RX_Section_Widget { protected $rx = 'hero'; }
class RX_W_kategorie extends RX_Section_Widget { protected $rx = 'kategorie'; }
class RX_W_uslugi extends RX_Section_Widget { protected $rx = 'uslugi'; }
class RX_W_jubileusz extends RX_Section_Widget { protected $rx = 'jubileusz'; }
class RX_W_premium extends RX_Section_Widget { protected $rx = 'premium'; }
class RX_W_oferta extends RX_Section_Widget { protected $rx = 'oferta'; }
class RX_W_serwis extends RX_Section_Widget { protected $rx = 'serwis'; }
class RX_W_nowosci extends RX_Section_Widget { protected $rx = 'nowosci'; }
class RX_W_promocje extends RX_Section_Widget { protected $rx = 'promocje'; }
class RX_W_blog extends RX_Section_Widget { protected $rx = 'blog'; }
class RX_W_aktualnosci extends RX_Section_Widget { protected $rx = 'aktualnosci'; }
class RX_W_galeria extends RX_Section_Widget { protected $rx = 'galeria'; }
class RX_W_oddzialy extends RX_Section_Widget { protected $rx = 'oddzialy'; }
