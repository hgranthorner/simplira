package main

import (
	"database/sql"
	"fmt"
	"html/template"
	"io/ioutil"
	"strings"

	_ "github.com/mattn/go-sqlite3"
)

type Ticket struct {
	id int
	name string
}

func GetTickets() (Ticket, error) {
	db, err := sql.Open("sqlite3", "../database.db")
	if err != nil {
		fmt.Printf("Failed to open database!")
		return Ticket{}, err
	}

	var ticket Ticket;
	res := db.QueryRow("select id, name from tickets limit 1;")
	err = res.Scan(&ticket.id, &ticket.name)

	if err != nil {
		fmt.Printf("Failed to select 1 ticket: %s", err)
		return Ticket{}, err
	}

	return ticket, err
}

func main() {
	buf, err := ioutil.ReadFile("index.go.html")
	if err != nil {
		fmt.Printf("%s", err)
		return
	}

	tmpl, err := template.New("foo").Parse(string(buf))
	if err != nil {
		fmt.Printf("%s", err)
		return
	}

	m := map[string]string {"Name1": "egg", "Name2": "Salad"}
	out := strings.Builder{}
	err = tmpl.Execute(&out, m)
	if err != nil {
		fmt.Printf("%s", err)
		return
	}

	fmt.Println(out.String())
}
