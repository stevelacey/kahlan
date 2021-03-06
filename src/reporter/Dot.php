<?php
namespace kahlan\reporter;

class Dot extends Terminal
{
    /**
     * Store the current number of dots.
     *é
     * @var integer
     */
    protected $_counter = 0;

    /**
     * Callback called before any specs processing.
     *
     * @param array $params The suite params array.
     */
    public function begin($params)
    {
        parent::begin($params);
        $this->write("\n");
    }

    /**
     * Callback called on successful expect.
     *
     * @param array $report The report array.
     */
    public function pass($report = [])
    {
        $this->_write('.');
    }

    /**
     * Callback called on failure.
     *
     * @param array $report The report array.
     */
    public function fail($report = [])
    {
        $this->_write('F', 'red');
    }

    /**
     * Callback called when an exception occur.
     *
     * @param array $report The report array.
     */
    public function exception($report = [])
    {
        $this->_write('E', 'magenta');
    }

    /**
     * Callback called on a skipped spec.
     *
     * @param array $report The report array.
     */
    public function skip($report = [])
    {
        $this->_write('S', 'cyan');
    }

    /**
     * Callback called when a `kahlan\IncompleteException` occur.
     *
     * @param array $report The report array.
     */
    public function incomplete($report = [])
    {
        $this->_write('I', 'yellow');
    }

    /**
     * Callback called at the end of specs processing.
     */
    public function end($results = [])
    {
        do {
            $this->_write(' ');
        } while ($this->_counter % 80 !== 0);

        foreach ($results['specs'] as $type => $reports) {
            foreach ($reports as $report) {
                if ($report['type'] !== 'skip') {
                    $this->_report($report);
                }
            }
        }

        $this->write("\n\n");
        $this->_summary($results);
        $this->_focused($results);
    }

    /**
     * Outputs the string message in the console.
     *
     * @param string       $string  The string message.
     * @param array|string $options The color options.
     */
    protected function _write($string, $options = null)
    {
        $this->write($string, $options);
        $this->_counter++;
        if ($this->_counter % 80 === 0) {
            $this->write(' ' . floor(($this->_current * 100) / $this->_total) . "%\n");
        }
    }
}
