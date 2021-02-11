<?php


namespace PonHelper\Storage;


interface StorageInterface
{
    function fill($model);
    function add($model);
    function delete($model);
    function update($model);
}