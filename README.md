CI test
========================

2) The XML library I chose is JMS Serializer, because using this library I'm able to work with the same objects
while deserializing the input and persisting the data in the database using Doctrine. The library would cause problems
with big input files, but nobody expected big input files in the test and I saved quite a lot of time, because
I didn't need to write a custom parser.

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
the code will be thrown away anyway, but if I wanted to optimize the query properly, so it would only search for the
requested paths, I would probably use this method: http://explainextended.com/2010/12/24/postgresql-parametrizing-a-recursive-cte/