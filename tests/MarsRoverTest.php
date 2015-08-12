<?php
/**
 * 
 * User: zingorn
 * Date: 12.08.2015
 * Time: 21:59
 */

class MarsRoverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function provider()
    {
        $dataPath = __DIR__ . '/data';
        $files = scandir($dataPath);

        $data = array();
        foreach ($files as $file) {
            $filePath = $dataPath . DIRECTORY_SEPARATOR . $file;
            if (!is_file($filePath) || $file == '..' || $file == '.') {
                continue;
            }

            if (!preg_match('/^([\d]+)\.test$/', $file, $fileName)) {
                continue;
            }

            $testName = $fileName[1];
            $data[$testName] = array(
                'input' => file_get_contents($filePath)
            );
        }

        return $data;
    }

    /**
     * @dataProvider provider
     * @param $data
     * @return void
     */
    public function testRoverMoveProgram($input)
    {
        $context = new \Nasa\Program\Context\PlatoContext();
        $program = new \Nasa\Program\MoveProgram();
        $program->
        var_dump($input);die;
        //$this->assertTrue($data);
    }
}