# Setting Up GraphQL, its types, queries, and resolvers

We should familiarize ourselves with the following GraphQL terminology:

- Types: Specifies the schema of our resource (field name, type, description, etc.)
- Queries: List of queries/actions that the client can make to our GraphQL application
- Resolvers: They fetch the data and return it to GraphQL for further processing. We can fetch the data from any data source, like a database, API, social media other GraphQL Apps, etc.
- Mutations: Mutations are just like queries. However, we use them to insert, update, and delete the data instead of just reading the data.


# Configuration
I've added a new field to configuration.json called graphql.
Add model to ignore in ignore_models.
Add the role of user that you want to access API.

```
"graphql": {
        "role": "user",
        "ignore_models": [
           "corporate"
        ]
    },
```

# Setup
Test using server.js

We will deploy this to AWS Lambda so later we will need to make sure lambda.js works.

When you test on http://127.0.0.1:1337/graphql , make sure you put Header Token

For now hardcode the user id you use graphql/src/services/auth_service.js

I will fix auth later.

```
{
  "Authorization": "Bearer a"
}
```

```
Run http://localhost:9001/generator.php?action=graphql
# It will take your model and create all resolver, schema.graphql, model in nodejs
cd graphql
# Update .env inside src folder
# Copy over config.js.sample to config.js
npm install
npm run dev
```

## Sample Mutation Input
```
# Query
query AuthorOperation {
  authors {
    firstName
  }
}

# Query Result
{
  "data": {
    "authors": [
      {
        "firstName": "a"
      },
      {
        "firstName": "b"
      },
      {
        "firstName": "aa"
      },
      {
        "firstName": "bv"
      }
    ]
  }
}

# Query with parameter
query PostGet($id: ID!) {
  post(id: $id) {
    id
    title
    content
    authorId
  }
}

# Query with parameter variables
{
  "id": 6
}

# Query with parameter result
{
  "data": {
    "post": {
      "id": "6",
      "title": "title",
      "content": "content",
      "authorId": "1"
    }
  }
}

# Mutation
mutation AddPost($title: String!, $content:String!, $authorId: ID!) {
  createPost(title: $title, content: $content, authorId: $authorId) {
    id
    title
    content
    authorId
    author {
      id
      firstName
    }
  }
}

# Query Variable
{
  "title": "title",
  "content": "content",
  "authorId": 2
}

# Return Value
{
  "data": {
    "createPost": {
      "id": "7",
      "title": "title",
      "content": "content",
      "authorId": "2",
      "author": {
        "id": "2",
        "firstName": "b"
      }
    }
  }
}
```

# GraphQL crash course
```
# Comments in GraphQL strings (such as this one) start with the hash (#) symbol.

# This "Book" type defines the queryable fields for every book in our data source.
type Book {
title: String
author: String
}

# The "Query" type is special: it lists all of the available queries that
# clients can execute, along with the return type for each. In this
# case, the "books" query returns an array of zero or more Books (defined above).

We've defined the objects that exist in our data graph, but clients don't yet have a way to fetch those objects. To resolve that, our schema needs to define queries that clients can execute against the data graph.

You define your data graph's supported queries as fields of a special type called the Query type.
type Query {
books: [Book]
launches: [Launch]!
launch(id: ID!): Launch
me: User
}

type Launch {
  id: ID!
  site: String
  mission: Mission
  rocket: Rocket
  isBooked: Boolean!
}
The Launch object type has a collection of fields, and each field has a type of its own. A field's type can be either an object type or a scalar type. A scalar type is a primitive (like ID, String, Boolean, or Int) that resolves to a single value.

An exclamation point (!) after a declared field's type means "this field's value can never be null."

If a declared field's type is in [Square Brackets], it's an array of the specified type. If an array has an exclamation point after it, the array cannot be null, but it can be empty.

type Mission {
  name: String
  missionPatch(size: PatchSize): String
}

type TripUpdateResponse {
  success: Boolean!
  message: String
  launches: [Launch]
}

Mutations
type Mutation {
  bookTrips(launchIds: [ID]!): TripUpdateResponse!
  cancelTrip(launchId: ID!): TripUpdateResponse!
  login(email: String): String # login token
}


```


### Resolver
```
parent: An object that contains the result returned from the resolver on the parent type
args: An object that contains the arguments passed to the field
context: An object shared by all resolvers in a GraphQL operation. We use the context to contain per-request state such as authentication information and access our data sources.
info: Information about the execution state of the operation which should only be used in advanced cases

module.exports = {
  Query: {
    launches: async (_, { pageSize = 20, after }, { dataSources }) => {
      const allLaunches = await dataSources.launchAPI.getAllLaunches();
      // we want these in reverse chronological order
      allLaunches.reverse();
      const launches = paginateResults({
        after,
        pageSize,
        results: allLaunches
      });
      return {
        launches,
        cursor: launches.length ? launches[launches.length - 1].cursor : null,
        // if the cursor of the end of the paginated results is the same as the
        // last item in _all_ results, then there are no more results after this
        hasMore: launches.length
          ? launches[launches.length - 1].cursor !==
            allLaunches[allLaunches.length - 1].cursor
          : false
      };
    },
    launch: (_, { id }, { dataSources }) =>
      dataSources.launchAPI.getLaunchById({ launchId: id }),
    me: (_, __, { dataSources }) => dataSources.userAPI.findOrCreateUser()
  }
};

# Always name query
query GetLaunches {
  launches {
    id
    mission {
      name
    }
  }
}
query GetLaunchById {
  launch(id: 60) {
    id
    rocket {
      id
      type
    }
  }
}
query GetLaunchById($id: ID!) {
  launch(id: $id) {
    id
    rocket {
      id
      type
    }
  }
}
# Resolver based on type
Mission: {
  // make sure the default size is 'large' in case user doesn't specify
  missionPatch: (mission, { size } = { size: 'LARGE' }) => {
    return size === 'SMALL'
      ? mission.missionPatchSmall
      : mission.missionPatchLarge;
  },
},
```

# Authentication
```
context: async ({ req }) => {
    // simple auth check on every request
    const auth = req.headers && req.headers.authorization || '';
    const email = Buffer.from(auth, 'base64').toString('ascii');
    if (!isEmail.validate(email)) return { user: null };
    // find a user by their email
    const users = await store.users.findOrCreate({ where: { email } });
    const user = users && users[0] || null;

    return { user: { ...user.dataValues } };
  },

Mutation: {
  login: async (_, { email }, { dataSources }) => {
    const user = await dataSources.userAPI.findOrCreateUser({ email });
    if (user) return Buffer.from(email).toString('base64');
  }
},
```

# Pageination
```
type Query {
  launches( # replace the current launches query with this one.
    """
    The number of results to show. Must be >= 1. Default = 20
    """
    pageSize: Int
    """
    If you add a cursor here, it will only return results _after_ this cursor
    """
    after: String
  ): LaunchConnection!
  launch(id: ID!): Launch
  me: User
}

"""
Simple wrapper around our list of launches that contains a cursor to the
last item in the list. Pass this cursor to the launches query to fetch results
after these.
"""
type LaunchConnection { # add this below the Query type as an additional type.
  cursor: String!
  hasMore: Boolean!
  launches: [Launch]!
}
```

# Dataloader
A batch loading function accepts an Array of keys, and returns a Promise which resolves to an Array of values.

# Deployment to AWS serverless lambda
https://claudiajs.com/tutorials/hello-world-lambda.html

```
npm install claudia -g

# Create new lambda
claudia create --region us-east-1 --handler lambda.handler

#invoke lambda
claudia test-lambda

#update lambda
claudia update

#Invoke with event
claudia test-lambda --event event.json

#Check logs
aws logs filter-log-events --log-group-name /aws/lambda/claudia-test
```

# Resources
```
https://github.com/dhruv-kumar-jha/awesome-graphql#lib-js
https://github.com/mickhansen/graphql-sequelize
https://github.com/Glavin001/graphql-sequelize-crud
https://github.com/pa-bru/graphql-cost-analysis
https://github.com/apollographql/apollo-server
https://github.com/dhruv-kumar-jha/graphql-doc

https://github.com/dhruv-kumar-jha/graphql-for-beginners

https://github.com/graphitejs/server
https://github.com/matthewmueller/graph.ql
https://github.com/devknoll/graphql-schema
https://github.com/graphql/express-graphql

https://github.com/apollographql/apollo-server/tree/master/packages/apollo-server-express
https://github.com/apollographql/apollo-server
https://github.com/sequelize/express-example
https://sequelize.org/master/manual/model-instances.html

```

```
~/my-function$ aws lambda update-function-code --function-name my-function --zip-file fileb://function.zip
{
    "FunctionName": "my-function",
    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function",
    "Runtime": "nodejs12.x",
    "Role": "arn:aws:iam::123456789012:role/lambda-role",
    "Handler": "index.handler",
    "CodeSha256": "Qf0hMc1I2di6YFMi9aXm3JtGTmcDbjniEuiYonYptAk=",
    "Version": "$LATEST",
    "TracingConfig": {
        "Mode": "Active"
    },
    "RevisionId": "983ed1e3-ca8e-434b-8dc1-7d72ebadd83d",
    ...
}
```