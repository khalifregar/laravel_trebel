# Build stage
FROM golang:1.22-alpine AS build

WORKDIR /app

COPY go.mod ./
COPY go.sum ./
RUN go mod download

COPY . .

# Anggap file utama lo ada di ./main.go atau ./cmd/server/main.go
RUN go build -o playlist-api ./cmd/server

# Final stage
FROM alpine:latest

WORKDIR /root/
COPY --from=build /app/playlist-api .

EXPOSE 3000

CMD ["./playlist-api"]
