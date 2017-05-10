<?php

class Mixtape_Model_DefinitionTest extends MixtapeModelTestCase {
    function setUp() {
        parent::setUp();
    }

    function test_exists() {
        $this->assertClassExists( 'Mixtape_Model_Definition' );
    }

    function test_add_model_definition() {
        $env = $this->mixtape->load()
            ->environment();
        $env->define_model( new Casette(), new Mixtape_Data_Store_Nil() );
        $model_definition = $env->model_definition( Casette::class );
        $this->assertInstanceOf( Mixtape_Model_Definition::class, $model_definition );
        $this->assertEquals( $model_definition->get_model_class(), Casette::class );
        $this->assertInstanceOf( Mixtape_Data_Store_Nil::class, $model_definition->get_data_store() );
    }

    function test_get_field_declarations() {
        $model_definition = $this->mixtape
            ->environment()
            ->define_model( new Casette(), new Mixtape_Data_Store_Nil() )
            ->model_definition( Casette::class );
        $declarations = $model_definition->get_field_declarations();
        $this->assertInternalType( 'array', $declarations );
        $this->assertEquals( 6, count( $declarations ) );
    }

    function test_create_with_array() {
        $casette = $this->mixtape
            ->environment()
            ->define_model( new Casette(), new Mixtape_Data_Store_Nil() )
            ->model_definition( Casette::class )
            ->create_instance( array(
                'title' => 'Awesome',
                'songs' => array(1,2,3)
            ) );

        $this->assertNotNull( $casette );
        $this->assertInstanceOf( Mixtape_Model::class, $casette );
    }

    function test_find_one_by_id_from_cpt_entity_return_null_when_id_not_in_db() {
        $casette_definition = $this->mixtape
            ->environment()
            ->define_model( new Casette(), new Mixtape_Data_Store_CustomPostType( 'mixtape_cassette' ) )
            ->model_definition( Casette::class );
        $model = $casette_definition->find_one_by_id( -1 );
        $this->assertNull( $model );
    }

    function test_find_one_by_id_from_cpt_entity_return_model() {
        $casette_definition = $this->mixtape
            ->environment()
            ->define_model( new Casette(), new Mixtape_Data_Store_CustomPostType( 'mixtape_cassette' ) )
            ->model_definition( Casette::class );

        $casette_to_insert = $casette_definition->create_instance( array(
            'title' => 'Awesomeness',
            'author' => get_current_user_id(),
            'status' => 'publish',
            'songs'  => array( 1, 2, 3 )
        ) );

        $id = $casette_definition->get_data_store()->upsert( $casette_to_insert );
        if ( is_wp_error( $id ) ) {
            var_dump($id);
        }
        $this->assertFalse( is_wp_error( $id ) );
        $model = $casette_definition->find_one_by_id( $id );
        $this->assertNotNull( $model );
        $this->assertInstanceOf( Mixtape_Model::class, $model );
        $model_id = $model->get( 'id' );
        $this->assertEquals( $id, $model_id );
        $model_meta_field = $model->get( 'songs' );
        $this->assertEquals( array( 1,2,3 ), $model_meta_field );
    }
}