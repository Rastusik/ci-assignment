CI assignment
========================

## How to run

You need to have [composer](https://getcomposer.org/) installed to run this code. The code is written in PHP 7 
and has been tested on Postgres 9.5

```bash

# this step will ask you for the database credentials
composer install

# creates database tables and views
./bin/console doctrine:migrations:migrate 

# to import the provided sample graph data:
./bin/console ci_test:importer:import

# to import custom graph data from url
./bin/console ci_test:importer:import -d http://yourdomain.com/graph.xml

# to run a json query (data file is included)
./bin/console ci_test:importer:query < data/query.sample.json
```

## Answers to questions

2) The XML library I chose is JMS Serializer, because using this library I'm able to work with the same objects
while deserializing the input and persisting the data in the database using Doctrine. The library would cause problems
with big input files, but nobody expected big input files in the test and I saved quite a lot of time, because
I didn't need to write a custom parser.

Also it is worth to mention, that I'm not checking the correctness of the input file, I'm validating domain entity data
parsed from the file. In this way I was able to achieve separation of concerns - 1) parsing, 2) validation, 3) persisting
into database. The code is much cleaner and extensible this way and I was able to reuse some general purpose libraries
and didn't have to write the low level code myself.

3) The SQL schema related to the XML input file of the graph is a 1:1 mapping. It is the best solution to have 
a normalized graph structure in a relational database (at least in my opinion).
The identifiers of the nodes and edges are of the type string, so it is easy to map them to the XML input
and it is easy to update them with XML file updates. (not working 100% right now, because nobody required this feature).
The nodes and edges are keeping their relation to the corresponding graph, so it is possible to import
multiple graph structures (under the condition that the node and edge identifiers stay unique between graphs).

4) SQL query for finding the cycles in the database:

```sql
with recursive paths (node, depth, path, cycle) as (
  select (select id from first_node), 0, ARRAY[(select id from first_node)], false
union all
  select to_id, depth + 1, (path || to_id::text), to_id = ANY(path)
  from edge, paths
  where from_id = node and not cycle
), first_node as (
  select id::text from node order by id offset 0 limit 1
) select path[array_position(path, path[array_upper(path, 1)]):array_upper(path, 1)] from paths where cycle
```

Warning - this query only works if there is at least one edge coming out of each node and if there are no 
separate clusters in the graph.

5) I created a view named 'path' which finds all the paths of the graph and can be restricted to find
only paths between two endpoints using a WHERE clause. The query is not optimal, because it always calculates all
the existing paths between all the nodes. Since this is just a test, I didn't want to optimize the query, because
the code will be thrown away anyway. If I wanted to optimize the query properly, so it would only search for the
requested paths, I would probably use this method: http://explainextended.com/2010/12/24/postgresql-parametrizing-a-recursive-cte/

I also modified the format of the input json query and the output json format, because the format from the assignment
is invalid (there are arrays with string keys in the example). The changes are visible in the sample json file in
the data folder.

For JSON parsing/serialization I chose the JMS Serializer library, for the same reasons as mentioned above.
  
*"Are there any paths problem"*: I used the same query, that is supposed to find cycles in the graph, I just
changed the final condition, so the results being returned contain no cycles. The query basically finds all the paths
from all the nodes to all the nodes (explained above). I created a view from the query, to be able to query the data 
using a Doctrine entity (a little hack). The same query also computes the cost of each path and returns all the paths
ordered by the computed cost. In this way, I was able to solve the *"cheapest path problem"*, because I'm re-using
the same query and appending a LIMIT 1 clause at the end of it.

The details are in the query, which is written in the app/DoctrineMigrations/Version20160214145352.php file. I'm
not really sure what more there is to explain, because it is a modified query from the Postgres documentation example
for the WITH clause. I only added the path cost computation and unique identifier generation (which made it more 
convenient for Doctrine).