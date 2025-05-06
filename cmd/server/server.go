package main

import (
	"log"

	"github.com/gofiber/fiber/v2"
	"playlist-api/internal/infrastructure/redisdb"

)

func Run() {
	app := fiber.New()

	redisdb.Init()

	app.Get("/", func(c *fiber.Ctx) error {
		err := redisdb.Client.Set(redisdb.Ctx, "ping", "pong", 0).Err()
		if err != nil {
			log.Println("Redis set error:", err)
			return c.SendStatus(500)
		}

		val, err := redisdb.Client.Get(redisdb.Ctx, "ping").Result()
		if err != nil {
			log.Println("Redis get error:", err)
			return c.SendString("Redis error")
		}

		return c.SendString("Redis value: " + val)
	})

	log.Println("🚀 Server running on http://localhost:3000")
	log.Fatal(app.Listen(":3000"))
}
