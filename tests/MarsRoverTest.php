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

            if (!preg_match('/^([\d]+)\.(in|out)$/', $file, $fileName)) {
                continue;
            }

            $testName = $fileName[1];
            if (!isset($data[$testName])) {
                $data[$testName] = array('in' => null, 'out' => array());
            }
            $data[$testName][$fileName[2]] = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        return $data;
    }

    /**
     * @dataProvider provider
     * @param $data
     * @return void
     */
    public function testRoverMoveProgram($input, $output)
    {
        list($maxX, $maxY) = explode(' ', array_shift($input));
        $context = new \Nasa\Program\Context\PlatoContext();
        $scope = new \Nasa\Model\Coordinate\CartesianScope();
        $scope->setOptions(array(
            'maxScopeX' => $maxX,
            'maxScopeY' => $maxY,
        ));
        $context->setScope($scope);

        $testCount = 1;
        while (count($input)) {
            $position = array_shift($input);
            $line = array_shift($input);

            $testTitle = sprintf('#%s: %s and program %s ', $testCount, $position, $line);
            $program = new \Nasa\Program\MoveProgram();
            $program->setContext($context);
            if ($position === null || $line === null) {
                $this->fail(sprintf('Wrong test config? Position: "%s"; Programm: "%s":  ' ,$position, $line));
            }
            $position = explode(' ', $position);
            if (count($position) != 3) {
                $this->fail('Invalid position string? Given: ' . implode(' ', $position));
            }
            $testOutput = array_shift($output);

            if ($testOutput === null) {
                $this->fail(sprintf('Wrong output string for test %s',$testTitle));
            }

            list($x, $y, $direction) = $position;

            $rover = new \Nasa\Model\Rover\Rover();
            $rover->setDirection(\Nasa\Model\Direction\CardinalDirection::createByAlias($direction));

            $roverPosition = new \Nasa\Model\Coordinate\CartesianCoordinate();
            $roverPosition->setX($x)->setY($y);
            $rover->setPosition($roverPosition);

            $program->setInput($line);
            $program->run($rover);

            if ($testOutput !== $program->getOutput()) {
                $this->fail(sprintf('Fail output of program for test %s. Expected %s, given %s.',$testTitle, $testOutput, $program->getOutput()));
            }
            echo "\n" . $program->getOutput();
            $testCount++;
        }

        $this->assertTrue(true);
        echo "\n";
    }
}