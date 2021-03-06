<?php

class GameRocket_Purchase extends GameRocket {
    
    public static function find($id) {
        self::_validateId($id);
        
        try {
            $response = GameRocket_Http::get('/games/' . GameRocket_Configuration::apikey() . '/purchases/' . $id);
            return self::factory($response['purchase']);
        } catch (GameRocket_Exception_NotFound $e) {
            throw new GameRocket_Exception_NotFound('Purchase with id ' . $id . ' not found.');
        }
    }
    
    public static function buy($id, $attribs = array()) {
        $response = GameRocket_Http::post('/games/' . GameRocket_Configuration::apikey() . '/purchases/' . $id . '/buy', $attribs);
        
        if (isset($response['error'])) {
            return new GameRocket_Result_Error($response);
        } else {
            return new GameRocket_Result_Successful(GameRocket_Map::factory($response));
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