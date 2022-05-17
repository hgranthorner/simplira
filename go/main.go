package main

import (
	"database/sql"
	"fmt"
	"html/template"
	"io/ioutil"
	"log"
	"strings"

	_ "github.com/mattn/go-sqlite3"
)

type Ticket struct {
	Id     int
	Name   string
	Status string
	Priority int
}

type Status struct {
	DisplayName string
	Value       string
}

var statuses = []Status{
	{DisplayName: "to do", Value: "to_do"},
	{DisplayName: "in progress", Value: "in_progress"},
	{DisplayName: "done", Value: "done"},
}

type Priority struct {
	DisplayName string
	Value       int
}

var priorities = []Priority{
	{DisplayName: "High", Value: 1},
	{DisplayName: "Medium", Value: 2},
	{DisplayName: "Low", Value: 3},
}

func main() {
	buf, err := ioutil.ReadFile("index.go.html")
	if err != nil {
		log.Fatal(err)
	}

	tmpl, err := template.New("foo").Parse(string(buf))
	if err != nil {
		log.Fatal(err)
	}

	tickets, err := GetTickets()
	if err != nil {
		log.Fatal("Failed to get tickets")
	}

	out := strings.Builder{}
	m := make(map[string]any)
	m["Tickets"] = tickets
	m["Statuses"] = statuses
	m["Priorities"] = priorities
	err = tmpl.Execute(&out, m)
	if err != nil {
		log.Fatal(err)
	}

	fmt.Println(out.String())
}

func GetTickets() ([]Ticket, error) {
	tickets := []Ticket{}
	err := Query(
		"../database.db",
		`select id, name, status, priority 
		from tickets
		where deleted_at is null;`,
		func(row Scannable) error {
			var ticket Ticket
			err := row.Scan(&ticket.Id, &ticket.Name, &ticket.Status, &ticket.Priority)
			if err != nil {
				return err
			}

			tickets = append(tickets, ticket)

			return nil
		},
	)

	if err != nil {
		return nil, err
	}

	return tickets, nil
}

type Scannable interface {
	Scan(dest ...any) error
}

func Query(connectionString string, query string, fn func(scannable Scannable) error) error {
	db, err := sql.Open("sqlite3", connectionString)

	if err != nil {
		return err
	}

	defer db.Close()

	rows, err := db.Query(query)

	if err != nil {
		return err
	}

	defer rows.Close()

	for rows.Next() {
		err = fn(rows)
		if err != nil {
			return err
		}
	}

	return nil
}
