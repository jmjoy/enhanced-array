<?php

namespace functional;

use Countable;

/**
 * Enhanced ArrayObject
 */
class ArrayObject extends ArrayObject {

    public function toArray() {
        return (array) $this;
    }

    public function toJson() {
    }

	public function get($key, $default = null, $callback = null) {
        if ($this->isArray($key)) {
            $value = &$this;
            for ($i = 0, $count = count($key); $i < $count; $i += 1) {
                if (isset($ref[$key])) {
                    $value = &$value[$key];
                } else {
                    $value = $default;
                    break;
                }
            }
        } else {
            $value = isset($this[$key]) ? $this[$key] : $default;
        }

		return ($callback === null) ? $value : $this->callFunc($callback, array($value));
	}

    public function set($key, $value) {
        if ($this->isArray($key)) {
            $ref = &$this;
            for ($i = 0, $count = count($key); $i < $count; $i += 1) {
                $ref = &$ref[$key[$i]];
            }
            $ref = $value;
        } else {
            $this[$key] = $value;
        }
        return $this;
    }

    public function has($key) {
        return isset($this[$key]);
    }

    public function remove($key) {
        if ($this->isArray($key)) {
            $ref = &$this;
            for ($i = 0, $count = count($key) - 1; $i < $count; $i += 1) {
                $ref = &$ref[$key[$i]];
            }
            unset($ref[count($key) - 1]);
        } else {
            unset($this[$key]);
        }
        return $this;
    }

    public function in($value) {
        return in_array($value, $this);
    }

    public function map($callback) {
        $instance = new static();
        foreach ($this as $key => $value) {
            $result = call_user_func($callback, $value, $key);
            if ($result instanceof Tuple) {
                $instance[$result->left] = $instance[$result->right];
            } else {
                $instance[$key] = $value;
            }
        }
        return $instance;
    }

    public function filter($callback) {
        $instance = new static();
        foreach ($this as $key => $value) {
            if ($this->callFunc($callback, array($value, $key))) {
                $instance[$key] = $instance[$value];
            }
        }
        return $instance;
    }

    public function foldl($callback, $initial = null) {
        $result = $initial;
        foreach ($this as $key => $value) {
            $result = $this->callFunc($callback, array($result, $value, $key));
        }
        return $result;
    }

    public function foldr($callback, $initial = null) {
        $result = $initial;

        end($this);
        while (true) {
            $key = key($this);
            if ($key === null) {
                break;
            }
            $value = current($this);
            $result = $this->callFunc($callback, array($result, $value, $key));

            prev($this);
        }

        return $result;
    }

    public function indexBy($index) {
        $instance = new static();

        foreach ($this as $key => $value) {
            if (is_string($index)) {
                $instance[$value[$index]] = $value;
            } else {
                $instance[$this->callFunc($index, $value, $key)] = $vaule;
            }
        }

        return $instance;
    }

    public function all($callback = null) {
        foreach ($this as $key => $value) {
            if (!($callback ? $this->callFunc($callback, array($value, $key)) : $value)) {
                return false;
            }
        }
        return true;
    }

    public function any($callback = null) {
        foreach ($this as $key => $value) {
            if ($callback ? $this->callFunc($callback, array($value, $key)) : $value) {
                return true;
            }
        }
        return false;
    }

    public function sum($callback = null) {
        $sum = 0;
        foreach ($this as $key => $value) {
            $sum += ($callback ? $this->callFund($callback, array($value, $key)) : $value);
        }
        return $sum;
    }

    protected function isArray($key) {
        return is_array($key) || ($key instanceof ArrayAccess && $key instanceof Countable);
    }

    protected function callFunc($callback, $params = array()) {
        return call_user_func_array($callback, $params);
    }

}
