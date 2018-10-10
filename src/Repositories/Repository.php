<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Exceptions\UndefinedMethodException;
use SecTheater\Marketplace\Repositories\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface {
	protected $scopeQuery, $query;
	protected $connector = 'and', $betweenQuery = null;
	public function __call($method, $arguments) {
		// Check for scope method and call
		if (method_exists($this, $scope = 'scope' . ucfirst($method))) {
			return call_user_func_array([$this, $scope], $arguments);
		}
		throw_if($method == 'scope' . ucfirst($method), UndefinedMethodException::class);
		if (starts_with($method, 'between')) {
			$finder = substr($method, 7);
			$segments = preg_split(
				'/(And|Or)(?=[A-Z])/', $finder, -1, PREG_SPLIT_DELIM_CAPTURE
			);
			$i = 0;
			foreach ($segments as $segment) {
				$segment = strtolower(snake_case($segment));
				if ($segment == 'or' || $segment == 'and') {
					$connectorIndex = $i;
					$this->connector = $segment;
					continue;
				}
				if (isset($connectorIndex) && $i > $connectorIndex) {
					$this->connector = 'and';
				}
				$this->parameters = count($arguments) == count($arguments, COUNT_RECURSIVE) ? $arguments : $arguments[$i];
				$this->setBetweenQuery(
					$this->chainBetween($segment)
				);
				$i++;
			}
			return $this->getBetweenQuery();
		}

		return $this->model->{$method}(...$arguments);
	}
	protected function between(string $column, $from, $to) {
		return ($this->getBetweenQuery() ?? $this->model)->whereBetween($column, [$from, $to], $this->connector);
	}
	protected function chainBetween($segment) {
		if (!$this->hasBetweenQuery()) {
			$this->setBetweenQuery(
				$this->between($segment, ...$this->parameters)
			);
			return $this->getBetweenQuery();
		}
		return $this->between($segment, ...$this->parameters);
	}
	public function getAll() {
		return $this->model->all();
	}

	public function getById($id) {
		return $this->model->find($id);
	}

	public function create(array $attributes) {
		return $this->model->create($attributes);
	}
	public function update($id, array $data) {
		return $this->model->findOrFail($id)->update($data);
	}

	public function delete($id) {
		return $this->model->delete();
	}
	/**
	 * Return query scope.
	 *
	 * @return array
	 */
	public function getScopeQuery() {
		return $this->scopeQuery;
	}
	public function getBetweenQuery() {
		return $this->betweenQuery;
	}
	public function setBetweenQuery($betweenQuery) {
		$this->betweenQuery = $betweenQuery;
	}
	public function hasBetweenQuery() {
		return !!$this->betweenQuery;
	}
	/**
	 * Add query scope.
	 *
	 * @param Closure $scope
	 *
	 * @return $this
	 */
	public function addScopeQuery(\Closure $scope) {
		$this->scopeQuery[] = $scope;
		return $this;
	}
	/**
	 * Apply scope in current Query
	 *
	 * @return $this
	 */
	protected function applyScope() {
		foreach ($this->scopeQuery as $callback) {
			if (is_callable($callback)) {
				$this->query = $callback($this->query);
			}
		}
		// Clear scopes
		$this->scopeQuery = [];
		return $this;
	}

}