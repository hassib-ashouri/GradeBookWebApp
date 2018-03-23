<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Loader class used primarily to implement namespaces in models
 * Class MY_Loader
 */
class MY_Loader extends CI_Loader
{
    private $CI;

    /**
     * {@inheritDoc}
     */
    public function model($model, $name = '', $db_conn = FALSE)
    {
        $shouldQuit = $this->_quitIf($model, $db_conn);
        if ($shouldQuit !== false) {
            return $shouldQuit;
        }

        $formatted = $this->_formatModel($model, $name);
        $path = $formatted["path"];
        $model = $formatted["model"];
        $name = $formatted["name"];
        $namespace = $formatted["namespace"];

        // quits if model initialized
        if (in_array($name, $this->_ci_models, TRUE)) {
            return $this;
        }

        $this->CI =& get_instance();
        if (isset($this->CI->$name)) {
            throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: ' . $name);
        }

        $this->_dbConn($db_conn);
        $this->_loadModelExtend();

        $model = ucfirst($model);
        $modelPath = $path . $model;
        $definedModel = $namespace . $model;
        $this->_autoloadModel($definedModel, $modelPath);

        $this->_ci_models[] = $name;
        $this->CI->$name = new $definedModel();
        return $this;
    }

    /**
     * Quits if $model is empty or an array
     *      creates models from it if it's an array
     *      returns null if it shouldn't quit
     * @param string|array $model
     * @param string|bool $db_conn
     * @return object|bool
     */
    private function _quitIf($model, $db_conn)
    {
        if (empty($model)) {
            // quits if $model is empty
            return $this;
        } elseif (is_array($model)) {
            // loops through if $model is an array
            foreach ($model as $key => $value) {
                if (is_int($key)) {
                    // meaningless keys
                    $this->model($value, '', $db_conn);
                } else {
                    // keys are model names, values are names to assign
                    $this->model($key, $value, $db_conn);
                }
            }
            return $this;
        }
        return false;
    }

    /**
     * Formats model and name into a few different parameters
     * @param string $model
     * @param string $name
     * @return array
     */
    private function _formatModel($model, $name)
    {
        $path = '';
        // default namespace for models
        $namespace = '\\Models\\';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($model, '/')) !== FALSE) {
            // The path is in front of the last slash
            $path = substr($model, 0, ++$last_slash);

            // And the model name behind it
            $model = substr($model, $last_slash);
        }

        // Does the model have a namespace? If so, parse out the filename and path.
        if (($last_f_slash = strrpos($model, '\\')) !== FALSE) {
            // The namespace is in front of the last slash
            $namespace = substr($model, 0, ++$last_f_slash);

            // And the model name behind it
            $model = substr($model, $last_f_slash);
        }

        // sets name to model name
        if (empty($name)) {
            $name = $model;
        }

        return array(
            "path" => $path,
            "model" => $model,
            "name" => $name,
            "namespace" => $namespace,
        );
    }

    /**
     * Initializes database loader if $db_conn isn't false
     * @param string|bool $db_conn
     */
    private function _dbConn($db_conn)
    {
        if ($db_conn !== FALSE && !class_exists('CI_DB', FALSE)) {
            if ($db_conn === TRUE) {
                $db_conn = '';
            }

            $this->database($db_conn, FALSE, TRUE);
        }
    }

    /**
     * Loads overwritten, and extended model core file
     */
    private function _loadModelExtend()
    {
        if (!class_exists('CI_Model', FALSE)) {
            $app_path = APPPATH . 'core' . DIRECTORY_SEPARATOR;
            if (file_exists($app_path . 'Model.php')) {
                require_once($app_path . 'Model.php');
                if (!class_exists('CI_Model', FALSE)) {
                    throw new RuntimeException($app_path . "Model.php exists, but doesn't declare class CI_Model");
                }
            } elseif (!class_exists('CI_Model', FALSE)) {
                require_once(BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Model.php');
            }

            $class = config_item('subclass_prefix') . 'Model';
            if (file_exists($app_path . $class . '.php')) {
                require_once($app_path . $class . '.php');
                if (!class_exists($class, FALSE)) {
                    throw new RuntimeException($app_path . $class . ".php exists, but doesn't declare class " . $class);
                }
            }
        }
    }

    /**
     * Automatically loads the required model
     * @param string $definedModel
     * @param string $modelPath
     */
    private function _autoloadModel($definedModel, $modelPath)
    {
        if (!class_exists($definedModel, FALSE)) {
            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'models/' . $modelPath . '.php')) {
                    continue;
                }

                require_once($mod_path . 'models/' . $modelPath . '.php');
                if (!class_exists($definedModel, FALSE)) {
                    throw new RuntimeException($mod_path . "models/" . $modelPath . ".php exists, but doesn't declare class " . $definedModel);
                }
                break;
            }

            if (!class_exists($definedModel, FALSE)) {
                throw new RuntimeException('Unable to locate the model you have specified: ' . $definedModel);
            }
        } elseif (!is_subclass_of($definedModel, 'CI_Model')) {
            throw new RuntimeException("Class " . $definedModel . " already exists and doesn't extend CI_Model");
        }
    }
}