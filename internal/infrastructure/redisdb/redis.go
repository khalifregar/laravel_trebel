package redisdb

import (
	"context"
	"fmt"
	"log"
	"os"

	"github.com/redis/go-redis/v9"
)

var (
	Ctx    = context.Background()
	Client *redis.Client
)

func Init() {
	// Ambil konfigurasi host & port dari environment
	host := os.Getenv("REDIS_HOST")
	if host == "" {
		host = "localhost"
	}

	port := os.Getenv("REDIS_PORT")
	if port == "" {
		port = "6379"
	}

	addr := fmt.Sprintf("%s:%s", host, port)

	// Inisialisasi Redis client
	Client = redis.NewClient(&redis.Options{
		Addr: addr,
	})

	// Tes koneksi dengan PING
	if err := Client.Ping(Ctx).Err(); err != nil {
		log.Fatalf("❌ Redis connection failed to %s: %v", addr, err)
	} else {
		log.Println("✅ Redis connected to", addr)
	}
}
