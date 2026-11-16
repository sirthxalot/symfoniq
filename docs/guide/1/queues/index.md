---
outline: deep
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Queues

## Introduction

While building your web application, you may have some tasks, such as parsing and storing an uploaded CSV file, that take too long to perform during a typical web request. Thankfully, {{ site.title }} allows you to easily create queued jobs that may be processed in the background. By moving time intensive tasks to a queue, your application can respond to web requests with blazing speed and provide a better user experience to your customers.

I have implemented [Redis] for queueing. If you followed the instructions for the development environment you should alredy installed [Redis] for PHP. Optional you can install [Predis] via Composer.

## Redis & Background Queues (Messenger)

This application uses **[Redis]** as an in-memory data store and message broker (queue), split across separate logical databases:

* **DB 0:** Application Cache (`cache.app`)
* **DB 1:** User Sessions
* **DB 2:** Rate Limiter & Locks
* **DB 3:** Messenger Queues (`async` & `async_high` streams)
* **DB 4:** Failed Messages (`failed` stream)

### How Services Run
* **Cache, Sessions & Rate Limiter** work automatically on every HTTP request—no background worker is required.
* **Messenger (Queues)** processes tasks asynchronously in the background via Redis Streams and requires an active consumer worker.

### Local Development
Start the Redis container via Docker:
```bash
docker compose up -d
```
When running the local server via **Symfony CLI** (`symfony server:start`), the Messenger worker (`messenger:consume`) starts and reloads automatically in the background via `.symfony.local.yaml`. No manual worker command is needed locally.

### Production Deployment
In production, you **must** run the Messenger worker continuously using a process manager (such as **Supervisor** or **systemd**) so queued messages and emails are processed:

```bash
php bin/console messenger:consume async_high async --time-limit=3600 --memory-limit=128M
```

> **Note on Deployments:** Always run `php bin/console messenger:stop-workers` after deploying new code so active workers finish their current job and restart with the updated codebase.


## Quick Overview via Terminal (redis-cli)

### See All Active Databases and Key Counts:

Run these commands in your WSL2 terminal while your Docker Redis container is running:

```bash
docker compose exec redis redis-cli INFO keyspace
```

***Example output:***

```bash
# Keyspace
db0:keys=12,expires=12,avg_ttl=3500000
db1:keys=1,expires=1,avg_ttl=1440000
db2:keys=2,expires=2,avg_ttl=58000
db3:keys=2,expires=0,avg_ttl=0
```

### List All Keys Inside a Specific Database

Replace -n 1 with 0, 1, 2, 3, or 4

```bash
docker compose exec redis redis-cli -n 1 KEYS "*"
```

### Inspect a Specific Key's Value or Stream

For Cache, Sessions, or Rate Limiter (DB 0–2):

```bash
docker compose exec redis redis-cli -n 1 GET "your_key_name"
```

For Messenger Streams (DB 3 or 4):

```bash
docker compose exec redis redis-cli -n 3 XINFO STREAM messages_high
docker compose exec redis redis-cli -n 3 XRANGE messages - +
```

### Watch Live Commands in Real Time

Press Ctrl + C to stop:

```bash
docker compose exec redis redis-cli MONITOR
```

<!--                  That's all folks!                 -->

[predis]: https://github.com/predis/predis
[redis]: https://redis.io/
