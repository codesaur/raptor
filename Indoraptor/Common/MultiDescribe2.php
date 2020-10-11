<?php namespace Indoraptor\Common;

use codesaur as single;
use codesaur\MultiModel\MultiDescribe;

class MultiDescribe2 extends MultiDescribe
{
    public function getTwigColumns(array $record = []) : array
    {
        $twigcolumns = array();
        
        foreach ($this->getPrimary()->getColumns() as $column) {
            $twigcolumns[$column->getName()] = array(
                'name' => $column->getPostName(),
                'length' => $column->getLength(),
                'type' => $column->getInputType(),
                'value' => $record[$column->getName()] ?? $column->getDefault()
            );
        }

        foreach ($this->getContent()->getColumns() as $column) {
            foreach (single::language()->codes() as $code) {
                $twigcolumns[$column->getName()][$code] = array(
                    'name' => $column->getPostName($code),
                    'length' => $column->getLength(),
                    'type' => $column->getInputType(),
                    'value' => $record[$column->getName()][$code] ?? $column->getDefault()
                );
            }
        }
        
        return $twigcolumns;
    }
}
