<?php

class GameRocket_App extends GameRocket {
    
    public static function find($id) {
        self::_validateId($id);
        
        try {
            $response = GameRocket_Http::get('/apps/' . $id);
            return self::factory($response['app']);
        } catch (GameRocket_Exception_NotFound $e) {
            throw new GameRocket_Exception_NotFound('App with id ' . $id . ' not found.');
        }
    }
    
    public static function factory($attributes) {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
    
    protected function _initialize($attributes) {
        $this->_attributes = $attributes;
        
        $this->_set('dynProp', new GameRocket_DynProp($attributes['dynProp']));
    }
    
    private static function _validateId($id = null) {
        if (empty($id)) {
            throw new InvalidArgumentException('Expected id to be set.');
        }
    }
}