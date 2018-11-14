<?php

namespace Yajra\DataTables\Html;

use Illuminate\Support\Collection;

/**
 * DataTables - Columns option builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasDataTablesColumnsOptions
{
    /**
     * Set columnDefs option value.
     *
     * @param array $value
     * @return $this
     * @see https://datatables.net/reference/option/columnDefs
     */
    public function columnDefs(array $value)
    {
        $this->attributes['columnDefs'] = $value;

        return $this;
    }

    /**
     * Set columns option value.
     *
     * @param array $columns
     * @return $this
     * @see https://datatables.net/reference/option/columns
     */
    public function columns(array $columns)
    {
        $this->collection = new Collection;

        foreach ($columns as $key => $value) {
            if (! is_a($value, Column::class)) {
                if (is_array($value)) {
                    $attributes = array_merge(
                        [
                            'name' => $value['name'] ?? $value['data'] ?? $key,
                            'data' => $value['data'] ?? $key,
                        ],
                        $this->setTitle($key, $value)
                    );
                } else {
                    $attributes = [
                        'name' => $value,
                        'data' => $value,
                        'title' => $this->getQualifiedTitle($value),
                    ];
                }

                $this->collection->push(new Column($attributes));
            } else {
                $this->collection->push($value);
            }
        }

        return $this;
    }

    /**
     * Add a column in collection usingsl attributes.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addColumn(array $attributes)
    {
        $this->collection->push(new Column($attributes));

        return $this;
    }

    /**
     * Add a Column object at the beginning of collection.
     *
     * @param \Yajra\DataTables\Html\Column $column
     * @return $this
     */
    public function addBefore(Column $column)
    {
        $this->collection->prepend($column);

        return $this;
    }

    /**
     * Add a column at the beginning of collection using attributes.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addColumnBefore(array $attributes)
    {
        $this->collection->prepend(new Column($attributes));

        return $this;
    }

    /**
     * Add a Column object in collection.
     *
     * @param \Yajra\DataTables\Html\Column $column
     * @return $this
     */
    public function add(Column $column)
    {
        $this->collection->push($column);

        return $this;
    }

    /**
     * Get collection of columns.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getColumns()
    {
        return $this->collection;
    }

    /**
     * Remove column by name.
     *
     * @param array $names
     * @return $this
     */
    public function removeColumn(...$names)
    {
        foreach ($names as $name) {
            $this->collection = $this->collection->filter(function (Column $column) use ($name) {
                return $column->name !== $name;
            })->flatten();
        }

        return $this;
    }
}
