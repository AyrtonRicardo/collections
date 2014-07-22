<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.

namespace Easy\Collections;

use Closure;
use Easy\Collections\Generic\IComparer;
use Easy\Collections\Linq\IQueryable;
use Easy\Collections\Linq\ISelectable;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 * Provides the abstract base class for a strongly typed collection.
 */
abstract class CollectionArray extends AbstractCollection implements
    IIndexAccess,
    IConstIndexAccess,
    IQueryable,
    ISelectable
{

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return isset($this->array[$key]) || array_key_exists($key, $this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->array, true);
    }

    /**
     * {@inheritdoc}
     */
    public function get($index)
    {
        if ($this->containsKey($index) === false) {
            throw new OutOfBoundsException('No element at position ' . $index);
        }

        return $this->array[$index];
    }

    /**
     * {@inheritdoc}
     * @param string $default
     */
    public function tryGet($index, $default = null)
    {
        if ($this->containsKey($index) === false) {
            return $default;
        }

        return $this->get($index);
    }

    /**
     * Sorts the elements in the entire Collection<T> using the specified comparer.
     * @param IComparer $comparer The ComparerInterface implementation to use 
     * when comparing elements, or null to use the default comparer 
     * Comparer<T>.Default.
     */
    public function sort(IComparer $comparer = null)
    {
        if ($comparer === null) {
            $comparer = $this->getDefaultComparer();
        }
        usort($this->array, array($comparer, 'compare'));

        return $this;
    }

    /**
     * Sorts the keys in the entire Collection<T> using the specified comparer.
     * @param IComparer $comparer The ComparerInterface implementation to use 
     * when comparing elements, or null to use the default comparer 
     * Comparer<T>.Default.
     */
    public function sortByKey(IComparer $comparer = null)
    {
        if ($comparer === null) {
            $comparer = $this->getDefaultComparer();
        }
        uksort($this->array, array($comparer, 'compare'));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($index)
    {
        if ($this->containsKey($index) == false) {
            throw new InvalidArgumentException('The key ' . $index . ' is not present in the dictionary');
        }
        unset($this->array[$index]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeValue($element)
    {
        $key = array_search($element, $this->array, true);

        if ($key !== false) {
            unset($this->array[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $p)
    {
        foreach ($this->array as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }
}
