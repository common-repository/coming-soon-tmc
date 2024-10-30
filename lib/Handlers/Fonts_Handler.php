<?php
namespace cs_tmc\lib\Handlers;


use cs_tmc\lib\App;

/**
 * Class Fonts_Handler
 *
 * Hello and welcome to my hell.
 * Check out constructor class. It's a good place to add some configuration.
 * You can add as many fonts as you want.
 * They are automatically added to font selectors in wp customizer.
 *
 * @author dualjack
 * @since 13.06.2017
 */
class Fonts_Handler {

    /**
     * @var App
     */
    public $app;
    /**
     * @var array
     */
    public $fontsStack = array();

    /**
     * @var array
     */
    public $fontsQueue = array();




    public function __construct( $app ) {

        $this->app = $app;

        //  -----------------------------------
        //  Add fonts to handler
        //  -----------------------------------

        $this->addFontsToStack(     //  <------
            array(

                'arimo'        =>  array(
                    'font_name'         =>  'Arimo',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Arimo:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'anton'  =>  array(
                    'font_name'         =>  'Anton',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Anton:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'baloo'  =>  array(
                    'font_name'         =>  'Baloo',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Baloo:400',
                    'backup_font_name'  =>  'cursive'  
                ),
                'bowlby_one'  =>  array(
                    'font_name'         =>  'Bowlby One',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Bowlby+One:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'bubbler_one'        =>  array(
                    'font_name'         =>  'Bubbler One',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Bubbler+One:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'droid_sans'        =>  array(
                    'font_name'         =>  'Droid Sans',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Droid+Sans:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'droid_serif'        =>  array(
                    'font_name'         =>  'Droid Serif',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Droid+Serif:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'indie_flower'  =>  array(
                    'font_name'         =>  'Indie Flower',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Indie+Flower:400,700,900',
                    'backup_font_name'  =>  'cursive'
                ),
                'josefin_sans'        =>  array(
                    'font_name'         =>  'Josefin Sans',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Josefin+Sans:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ), 
                'knewave'  =>  array(
                    'font_name'         =>  'Knewave',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Knewave:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'lato'        =>  array(
                    'font_name'         =>  'Lato',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Lato:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'lora'        =>  array(
                    'font_name'         =>  'Lora',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Lora:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'muli'        =>  array(
                    'font_name'         =>  'Muli',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Muli:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'monotone'  =>  array(
                    'font_name'         =>  'Monotone',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Monotone:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'open_sans'     =>  array(
                    'font_name'         =>  'Open Sans',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Open+Sans:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'open_sans_condensed'        =>  array(
                    'font_name'         =>  'Open Sans Condensed',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'orbitron'  =>  array(
                    'font_name'         =>  'Orbitron',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Orbitron:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'oswald'        =>  array(
                    'font_name'         =>  'Oswald',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Oswald:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'pacifico'  =>  array(
                    'font_name'         =>  'Pacifico',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Pacifico:400,700,900',
                    'backup_font_name'  =>  'cursive'  
                ),
                'pangolin'        =>  array(
                    'font_name'         =>  'Pangolin',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Pangolin:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'permanent_marker'  =>  array(
                    'font_name'         =>  'Permanent Marker',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Permanent+Marker:400,700,900',
                    'backup_font_name'  =>  'cursive'
                ),
                'playfair_display'        =>  array(
                    'font_name'         =>  'Playfair Display',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'pt_sans'        =>  array(
                    'font_name'         =>  'PT Sans',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=PT+Sans:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'pt_sans_narrow'        =>  array(
                    'font_name'         =>  'PT Sans Narrow',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'poppins'        =>  array(
                    'font_name'         =>  'Poppins',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Poppins:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'roboto'        =>  array(
                    'font_name'         =>  'Roboto',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Roboto:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'roboto_slab'        =>  array(
                    'font_name'         =>  'Roboto Slab',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Roboto+Slab:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'raleway'           =>  array(
                    'font_name'         =>  'Raleway',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Raleway:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'spectral'        =>  array(
                    'font_name'         =>  'Spectral',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Spectral:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'slabo_27px'        =>  array(
                    'font_name'         =>  'Slabo 27px',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Slabo+27px:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'source_sans_pro'        =>  array(
                    'font_name'         =>  'Source Sans Pro',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'titillium_web'        =>  array(
                    'font_name'         =>  'Titillium Web',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Titillium+Web:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'montserrat'        =>  array(
                    'font_name'         =>  'Montserrat',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Montserrat:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'merriweather'        =>  array(
                    'font_name'         =>  'Merriweather',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Merriweather:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'noto_sans'        =>  array(
                    'font_name'         =>  'Noto Sans',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Noto+Sans:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                ),
                'ubuntu'        =>  array(
                    'font_name'         =>  'Ubuntu',
                    'font_url'          =>  'https://fonts.googleapis.com/css?family=Ubuntu:400,700,900&amp;subset=latin-ext',
                    'backup_font_name'  =>  'sans-serif'
                )
            )
        );

    }

    /**
     * Safe way of adding fonts to queue.
     *
     * @param string $fontSlug
     *
     * @return bool - font added to queue
     */
    public function addFontToQueue( $fontSlug ) {

        if( isset( $this->fontsStack[$fontSlug] ) ){

            $this->fontsQueue[] = $fontSlug;
            $this->fontsQueue = array_unique( $this->fontsQueue );

            return true;

        } else {

            return false;

        }

    }

    /**
     * Call this function to start queueing fonts.
     * Just pass array of options with fontSlugs as values.
     *
     * @example 'my_option' => array( 'something' => 'open_sans', 'apple' => 'roboto' );
     *
     * @param array $optionWithUsedFontSlugs
     */
    public function initFontsQueue( array $optionWithUsedFontSlugs ) {

        foreach( $optionWithUsedFontSlugs as $fontSlug ){

            $this->addFontToQueue( $fontSlug );

        }

    }

    /**
     * More direct way of adding fonts.
     *
     * @param array $arrayOfFontsProperties
     * '(slug)' =>  array(
     *      'font_name'         =>  string,
     *      'font_url'          =>  string,
     *      'backup_font_name'  =>  string
     * )
     */
    public function addFontsToStack( $arrayOfFontsProperties ) {

        $this->fontsStack = array_merge( $this->fontsStack, $arrayOfFontsProperties );

    }

    /**
     * Returns html of font family.
     *
     * @param string $fontSlug
     *
     * @return string $fontFamily
     */
    public function getFontFamily( $fontSlug ) {

        if( isset( $this->fontsStack[$fontSlug] ) ){

            $font = $this->fontsStack[$fontSlug];

            if( isset( $font['backup_font_name'] ) && ! empty( $font['backup_font_name'] ) ){

                return sprintf( '\'%1$s\', %2$s', $font['font_name'], $font['backup_font_name'] );

            } else {

                return sprintf( '\'%1$s\'', $font['font_name'] );

            }

        } else {

            return sprintf( 'sans-serif' );

        }

    }

    /**
     * Generates embedding code.
     *
     * @return string $html - code for embedding fonts in page
     */
    public function getEmbedHtml() {

        $html = '';

        foreach( $this->fontsQueue as $fontSlug ) {

            if( isset( $this->fontsStack[$fontSlug] ) ){

                $font = $this->fontsStack[$fontSlug];

                if( isset( $font['font_url'] ) && ! empty( $font['font_url'] ) ){

                    $html .= sprintf( '<link href="%1$s" rel="stylesheet">', $font['font_url'] ) . PHP_EOL;

                }

            }

        }

        return $html;

    }

    /**
     * Returns array of choices.
     * SLUG <-> NAME
     *
     * @return array
     */
    public function getChoices() {

        $choices = array();

        foreach( $this->fontsStack as $key => $value ){

            $choices[$key] = $value['font_name'];

        }

        return $choices;

    }
}