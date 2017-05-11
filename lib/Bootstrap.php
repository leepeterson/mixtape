<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mixtape_Bootstrap {

    /**
     * @var null|object the Environment implementation
     */
    private $environment = null;

    /**
     * @var null|Mixtape_Class_Loader
     */
    private $class_loader = null;

    /**
     * Mixtape_Bootstrap
     * - Loads Classes
     * - Creates Environment instance
     * @param null|Mixtape_Interfaces_Class_Loader $class_loader optional class loader overrride
     */
    private function __construct( $class_loader = null ) {
        $this->class_loader = $class_loader;
    }

    /**
     * @param Mixtape_Interfaces_Class_Loader|null $class_loader
     * @return Mixtape_Bootstrap
     */
    public static function create( $class_loader = null ) {
        if ( empty( $class_loader ) ) {
            include_once ( 'Interfaces/Class/Loader.php' );
            include_once ( 'Class/Loader.php' );
            $prefix = str_replace( '_Bootstrap', '', __CLASS__ );
            $base_dir = untrailingslashit( dirname( __FILE__ ) );
            $class_loader = new Mixtape_Class_Loader( $prefix, $base_dir );
        }
        return new self( $class_loader );
    }

    /**
     * Instead of calling load() you can
     * register as an auto-loader
     * @return Mixtape_Bootstrap $this
     */
    function register_autoload() {
        if ( function_exists( 'spl_autoload_register' ) ) {
            spl_autoload_register( array( $this->class_loader(), 'load_class' ), true );
        }
        return $this;
    }

    /**
     * Load all classes
     * @return Mixtape_Bootstrap $this
     * @throws Exception
     */
    function load() {
        $this->class_loader()
            ->load_class( 'Interfaces_Data_Store' )
            ->load_class( 'Interfaces_Model' )
            ->load_class( 'Interfaces_Builder' )
            ->load_class( 'Interfaces_Model_Collection' )
            ->load_class( 'Interfaces_Model_Declaration' )
            ->load_class( 'Interfaces_Rest_Api_Controller_Bundle' )
            ->load_class( 'Exception' )
            ->load_class( 'Environment' )
            ->load_class( 'Data_Serializer' )
            ->load_class( 'Data_Mapper' )
            ->load_class( 'Data_Store_Nil' )
            ->load_class( 'Data_Store_Abstract' )
            ->load_class( 'Data_Store_CustomPostType' )
            ->load_class( 'Data_Store_Option' )
            ->load_class( 'Model_Field_Types' )
            ->load_class( 'Model_Field_Sanitizer' )
            ->load_class( 'Model_Field_Declaration' )
            ->load_class( 'Model_Field_DeclarationBuilder' )
            ->load_class( 'Model_Declaration' )
            ->load_class( 'Model_Definition' )
            ->load_class( 'Model' )
            ->load_class( 'Model_Collection' )
            ->load_class( 'Rest_Api_Controller' )
            ->load_class( 'Rest_Api_Controller_Builder' )
            ->load_class( 'Rest_Api_Controller_ModelBase' )
            ->load_class( 'Rest_Api_Controller_Settings' )
            ->load_class( 'Rest_Api_Controller_CRUD' )
            ->load_class( 'Rest_Api_Controller_CRUD_Builder' )
            ->load_class( 'Rest_Api_Controller_Bundle' )
            ->load_class( 'Rest_Api_Controller_Bundle_Definition' )
            ->load_class( 'Rest_Api_Controller_Bundle_Builder' );
        return $this;
    }

    /**
     * Load Unit Testing Base Classes
     * @return Mixtape_Bootstrap $this
     */
    function load_testing_classes() {
        $this->class_loader()
            ->load_class( 'Testing_TestCase' )
            ->load_class( 'Testing_Model_TestCase' )
            ->load_class( 'Testing_Controller_TestCase' );
        return $this;
    }

    /**
     * @return Mixtape_Class_Loader
     */
    function class_loader() {
        return $this->class_loader;
    }

    /**
     * Lazy-load the environment
     * @return Mixtape_Environment
     */
    public function environment() {
        if ( null === $this->environment ) {
            $this->environment = new Mixtape_Environment( $this );
        }
        return $this->environment;
    }
}