<?php

/*
 ===========================================================================
 = Autoloader
 ===========================================================================
 =
 = Carrega toda a carga de classes da aplicação dinamicamente, fornecendo
 = uma programação mais limpa e flexivel.
 = 
 */

use \RuntimeException;
use \InvalidArgumentException;

/**
 * PHP Autoloader
 * @link https://github.com/lleocastro/autoload/
 * @license (MIT) https://github.com/lleocastro/autoload/blob/master/LICENSE
 * @author Leonardo Carvalho <leonardo_carvalho@outlook.com>
 * @package \Includder\Loader;
 * @copyright 2016 
 * @version 1.1.3
 */
class Autoloader
{
    /**
     * Caminhos para indicar ao autoload o que importar.
     * Defina os 'paths' na key 'autoload' no appdata na raiz do projeto.
     * @var array
     */
    protected static $paths = [];
    
    /**
     * Carrega configurações adicionais para o autoload.
     * Defina uma extensão para os arquivos: ['php', 'class.php', inc.php]
     * @var array
     */
    private static $config = [];
    
    /**
     * Ativa o suporte a namespaces seguindo a especificação da
     * PSR-4. Exemplo: namespace Src\Loader; use \Src\Loader\Autoloader;
     * @var boolean
     */
    private static $namespacesSupport = true;

    /**
     * This autoload demand this file for initialize.
     * File name customization.
     * @var string
     */
    private static $fileName = '';
    private static $fileExt = '.json';
    
    /**
     * Namespace customization.
     * @var string
     */
    protected static $projectName = '';

    /**
     * @var string
     */
    protected static $separator = DIRECTORY_SEPARATOR;
    
    /**
     * Para buscas em toda a aplicação
     * são definidos niveis de subdiretórios que a aplicação
     * possui, desde a pasta a qual este autoload está.
     * até a raiz do projeto.
     * @var array
     */
    protected static $levels = [];
    
    /**
     * @var string
     */
    private static $thisDir = __DIR__;
    
    //Static Access
    private function __construct()
    {}
    
    /**
     * Inicializa o autoload
     * @return void
     */
    public static function run(array $data)
    {
        if((array_key_exists('dirlevel', $data))):
            self::$fileName = (is_string($data['filename']))?$data['filename']:'appdata';
            self::$namespacesSupport = ((is_bool($data['namespaces']))?$data['namespaces']:true);
            self::$projectName = ((is_string($data['projectname']))&&(!empty($data['projectname'])))?$data['projectname']:'';
            self::levelGenerate($data['dirlevel']);
            self::getData();
            self::loader();
            return true;
        endif;
        
        /**
         * Retorna uma exceção caso não encontre o indice "dirlevel" no array.
         * @throws RuntimeException
         */
        throw new RuntimeException("Key 'dirlevel' not found! run(['dirlevel' => ])!");
    }
    
    /**
     * Gera os niveis de retorno para ir subindo niveis de subdiretórios
     * até a raiz da aplicação.
     * @return void
     */
    protected static function levelGenerate($dirLevel)
    {
        if((is_int($dirLevel)) && (!empty($dirLevel))):
            if($dirLevel != 3): 
                $dirLevel += 3; 
            endif;
            
            self::$levels = null;
            self::$levels[0] = '/../';
            
            if((is_int($dirLevel)) && (($dirLevel > 0) && ($dirLevel <= 15))):
                for($i= 1; $i < $dirLevel; $i++):
                    self::$levels[$i] = self::$levels[0] . self::$levels[$i-1];
                endfor;
            endif;
        else:
            
            /**
             * Retorna uma exceção caso não tenha um valor valido no "dirlevel"
             * @throws InvalidArgumentException 
             */
            throw new InvalidArgumentException("Value of 'dirlevel' defined in 'run()' invalid!");
        endif;
    }

    /**
     * Carrega os dados necessarios para o funcionamento do autoload.
     * @return void
     */
    protected static function getData()
    {
        /**
         * Arquivo a ser procurado
         * @var string
         */
        $file = self::$fileName . self::$fileExt;

        /**
         * Status para saber se o arquivo arquivo foi encontrado.
         * @var boolean
         */
        $notFind = true;
        
        //Procura pelo arquivo a partir desta pasta até a raiz da aplicação.
        foreach(self::$levels as $level):
            $level = str_replace(str_replace('//', '/', $level), self::$separator, $level);
            $baseDir = self::$thisDir . $level;
            if(($notFind) && (is_readable($baseDir . $file))):
                $appData = json_decode(file_get_contents($baseDir . $file), true);
                //Faz a requisição e puxa os dados referente ao autoload, apenas.
                $data = $appData['autoload'];
                unset($appData);
                
                if(!empty($data)):
                    self::$paths = $data['paths'];
                    self::$config = $data['configs'];
                    unset($data);
                endif;
                
                $notFind = false;
                break;
            endif;
        endforeach;
        
        /**
         * Retorna uma exceção caso não encontre a o arquivo de configurações.
         * @throws RuntimeException
         */
        if($notFind):
            throw new RuntimeException("File '{$file}' not found in '{$baseDir}'!");
        endif;
    }

    /**
     * Autoload
     * @return void
     */
    private static function loader()
    {
        spl_autoload_register(function($className){
            
            //Ativa o suporte a namespace
            if(self::$namespacesSupport):
                if((strstr($className, '\\')) || (str_word_count($className) > 1)):
                    $className = self::getNameSpace($className);
                else:
                    
                    /**
                     * Retorna uma exceção se o namespace não estiver definido corretamente.
                     * @throws RuntimeException
                     */
                    throw new RuntimeException("Namespace not defined in '{$className}'.");
                endif;
            endif;

            /**
             * Caminho completo da classe.
             * @var string
             */
            $classFile = '';

            /**
             * Define o diretório raiz e o separador que os divide.
             * @var string
             */
            $separator = self::$separator;
            $baseDir = self::$thisDir . $separator . '..';

            /**
             * Checa se a classe já foi incluida.
             * @var boolean
             */
            $classIncluded = false;

            /**
             * Define a extenção dos arquivos.
             * @var array
             */
            $ext = self::$config['ext'];

            //Substitui a '/' pelo separador padrão do sistema hospedeiro
            foreach(self::$paths as $dir):
                if((substr($dir, -1) !== $separator)):
                    $dir .= (substr($dir, -1) == '/')?str_replace('/', $separator, $dir):$separator;
                endif;
                
                //Faz uma busca baseada nos niveis da aplicação definidos no 'dirlevel'
                foreach(self::$levels as $level):
                    $level = str_replace(str_replace('//', '/', $level), self::$separator, $level);
                    $baseDir = self::$thisDir . $level;
                    if(is_readable(self::$thisDir . $level . "{$dir}{$className}.{$ext}")):
                        $classFile = $baseDir . "{$dir}{$className}.{$ext}";
                        break;
                    endif;
                endforeach;
                
                //Faz o include da classe
                if((!$classIncluded) && (is_readable($classFile)) && (!is_dir($classFile))):
                    require_once (\str_replace('//', '/', $classFile));
                    $classIncluded = true;
                endif;
            endforeach;

            /**
             * Retorna uma exceção caso não encontre a classe requisitada.
             * @throws RuntimeException 
             */
            if(!$classIncluded):
                throw new RuntimeException("Class '{$className}.{$ext}' not found in '{$baseDir}'!");
            endif;
        });
    }

    /**
     * Namespace
     * Formata o '$className' seguindo a especificação da PSR-4
     * @param string
     * @return string
     */
    private static function getNameSpace($class)
    {
        $className = ltrim($class, '\\');
        
        //Custom namespace
        if(!empty(self::$projectName)):
            $className = ltrim($class, self::$projectName . '\\');
        endif;
        
        $fileName  = '';
        $namespace = '';
        
        if($lastNsPos = strrpos($className, '\\')):
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', self::$separator, $namespace) . self::$separator;
        endif;
        
        $fileName = strtolower($fileName . $className);
        $last = strrchr($fileName, '/');
        $uc = '/' . ucfirst(substr(strrchr($fileName, '/'), strrchr($fileName, '/') + 1));
        
        return $className;
    }

}