<?php

namespace AjaxLiveSearch\core;

if (count(get_included_files()) === 1) {
    exit('Direct access not permitted.');
}

/**
 * Class Config
 */
class Config
{
    /**
     * @var array
     */
    private static $configs = array(
        // ***** Database ***** //
        'dataSources'           => array(
            'ls_query' => array(
                'host'               => 'localhost',
                'database'           => 'web',
                'username'           => 'root',
                'pass'               => 'sayan0711',
                'table'              => 'Student',
                // specify the name of search columns
                'searchColumns'      => array('name'),
                // specify order by column. This is optional
                'orderBy'            => '',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                // filter the result by entering table column names
                // to get all the columns, remove filterResult or make it an empty array
                'filterResult'       => array('name','uname'),
                // specify search query comparison operator. possible values for comparison operators are: 'LIKE' and '='. this is required.
                'comparisonOperator' => 'LIKE',
                // searchPattern is used to specify how the query is searched. possible values are: 'q', '*q', 'q*', '*q*'. this is required.
                'searchPattern'      => 'q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                'maxResult' => 10,
                // to display column header, change 'active' value to true
                'displayHeader' => array(
                    'active' => true,
                    'mapper' => array(
                       'name' => 'Full Name',
                       'uname' => 'Username'
                    )
                ),
                'type'               => 'mysql',
            ),
            'ls_query_2' => array(
                'host'               => 'localhost',
                'database'           => 'web',
                'username'           => 'root',
                'pass'               => 'sayan0711',
                'table'              => 'Teacher',
                // specify the name of search columns
                'searchColumns'      => array('name','uname'),
                // specify order by column. This is optional
                'orderBy'            => '',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                // filter the result by entering table column names
                // to get all the columns, remove filterResult or make it an empty array
                'filterResult'       => array('name','uname'),
                // specify search query comparison operator. possible values for comparison operators are: 'LIKE' and '='. this is required.
                'comparisonOperator' => 'LIKE',
                // searchPattern is used to specify how the query is searched. possible values are: 'q', '*q', 'q*', '*q*'. this is required.
                'searchPattern'      => 'q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                //'maxResult' => 3,
                // to display column header, change 'active' value to true
                'displayHeader' => array(
                    'active' => true,
                    'mapper' => array(
                        'name' => 'Faculty Name',
                        'uname' => 'username'

                        //'phone' => 'Phone'
                    )
                ),
                'type'               => 'mysql',
            ),
            'ls_query_3' => array(
                'host'               => 'localhost',
                'database'           => 'web',
                'username'           => 'root',
                'pass'               => 'sayan0711',
                'table'              => 'Student',
                // specify the name of search columns
                'searchColumns'      => array('name'),
                // specify order by column. This is optional
                'orderBy'            => '',
                // specify order direction e.g. ASC or DESC. This is optional
                'orderDirection'     => '',
                // filter the result by entering table column names
                // to get all the columns, remove filterResult or make it an empty array
                'filterResult'       => array('name','uname'),
                // specify search query comparison operator. possible values for comparison operators are: 'LIKE' and '='. this is required.
                'comparisonOperator' => 'LIKE',
                // searchPattern is used to specify how the query is searched. possible values are: 'q', '*q', 'q*', '*q*'. this is required.
                'searchPattern'      => 'q*',
                // specify search query case sensitivity
                'caseSensitive'      => false,
                // to limit the maximum number of result uncomment this:
                'maxResult' => 10,
                // to display column header, change 'active' value to true
                'displayHeader' => array(
                    'active' => true,
                    'mapper' => array(
                       'name' => 'Full Name',
                       'uname' => 'Username'
                    )
                ),
                'type'               => 'mysql',
            ),
            'mainMongo' => array(
                'server'       => 'your_server',
                'database'     => 'local',
                'collection'   => 'your_collection',
                'filterResult' => array(),
                'searchField'  => 'your_collection_search_field',
                'type'         => 'mongo',
            )
        ),
        // ***** Form ***** //
        'antiBot'               => "ajaxlivesearch_guard",
        // Assigning more than 3 seconds is not recommended
        'searchStartTimeOffset' => 2,
        // ***** Search Input ***** /
        'maxInputLength'        => 20,
    );

    /**
     *
     * @param  $key
     * @throws \Exception
     * @return mixed
     */
    public static function getConfig($key)
    {
        if (!array_key_exists($key, static::$configs)) {
            throw new \Exception("Key: {$key} does not exist in the configs");
        }

        return static::$configs[$key];
    }
}
