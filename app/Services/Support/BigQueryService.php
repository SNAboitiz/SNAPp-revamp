<?php

namespace App\Services\Support;

use App\Services\Support\Service;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\QueryResults;

class BigQueryService extends Service
{
    /**
     * The BigQuery client instance.
     *
     * @var BigQueryClient
     */
    protected BigQueryClient $bigQuery;

    /**
     * Create a new BigQueryService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bigQuery = new BigQueryClient([
            'projectId' => config('services.google_cloud.project_id'),
            'keyFilePath' => config('services.google_cloud.key_path'),
        ]);
    }

    /**
     * Execute a SQL query against BigQuery.
     *
     * @param string $sql The SQL query to execute.
     * @param array $parameters Optional parameters for the query.
     * @return QueryResults The results of the query.
     */
    protected function query(string $sql, array $parameters = []): QueryResults
    {
        $jobConfig = $this->bigQuery->query($sql);

        if (!empty($parameters)) {
            $jobConfig->parameters($parameters);
        }

        return $this->bigQuery->runQuery($jobConfig);
    }

    /**
     * Query a table with optional filters and return the results as an array.
     *
     * @param string $table The table to query.
     * @param array $filters Optional associative array of filters (field => value).
     * @param array $columns Optional array of columns to select (default is all).
     * @param int $limit Optional limit on the number of results (default is 1000).
     * @return array The query results as an array.
     */
    protected function queryWithFilters(string $table, array $filters = [], array $columns = ['*'], int $limit = 1000): array
    {
        $selectColumns = implode(', ', $columns);
        $sql = "SELECT {$selectColumns} FROM `{$table}`";

        $whereConditions = [];
        $parameters = [];

        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $placeholders = [];
                foreach ($value as $index => $item) {
                    $paramName = $field . '_' . $index;
                    $placeholders[] = '@' . $paramName;
                    $parameters[$paramName] = $item;
                }
                $whereConditions[] = "{$field} IN (" . implode(', ', $placeholders) . ")";
            } else {
                $whereConditions[] = "{$field} = @{$field}";
                $parameters[$field] = $value;
            }
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }

        $sql .= " LIMIT {$limit}";

        $results = $this->query($sql, $parameters);

        $rows = [];
        foreach ($results as $row) {
            $rows[] = $row;
        }

        return $rows;
    }
}
