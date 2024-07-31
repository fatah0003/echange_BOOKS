load/fixtures:
	symfony console d:d:d --force || true
	symfony console d:d:c
	symfony console d:m:m -n
	symfony console d:f:l -n

.PHONY: load/fixtures