## Opis
Aplikacja zawiera logikę biznesową oraz testowe implementacje repozytoriów.

Jej działanie opiera się o 
- asynchroniczną szynę zdarzeń, która ma zapewnić skalowalność i szybką obsługę wielu zdarzeń nie blokując głównej aplikacji.
- notyfikacje rozsyłane do klientów w momencie nadania zdarzenia
- zachowywanie danych historycznych przez przechowywanie eventów

### Uwaga
Ponieważ do prezentacji koncepcji aplikacja oparta jest na tymczasowej pamięciowej bazie danych skupiłem się na logice biznesowej i abstrakcji, zostawiając decyzję o konkretnych rozwiazaniach (np kafka vs rabbitmq, postgres vs baza nosql) na później.
Ograniczyłem też testy do prostych testów jednostkowych z tego względu.

Wykorzystałem szkielet zawartej w przykładzie aby szybieciej przygotować działajacy prototyp.

## Komponenty

### MessageBus
Dla przykładu zastosowano prostą pamięciową szynę na obsługę zdarzeń (implementacja `MessageBusInterface`). W efekcie aplikacja działa synchronicznie.
W docelowym systemie należy zastosować oddzielny kontener który będzie można łatwo replikować i który uruchomi się jako consumer implementujący `ConsumerInterface`.

Szyna obsługuje wiadomości o typie `MatchEvent`. W zależności od typu kierowane są one następnie do odpowiedniego handlera (application/handlers), który deleguje aktualizacje statystyk i zapisuje zdarzenie.

### Encje i repozytoria
Encja `MatchEvent` odzwierciedla pojedyńcze zdarzenie - np gol lub fault (w zależności od typu)
Encja `MatchStatistics` zawiera informacje na temat statystyk w danym meczu dla danej drużyny.
Encje  są obsługiwane przez repozytoria StatisticsRepositoryInterface i EventRepositoryInterface.

### Statystyki
Encja `MatchStatistics` zawiera metode `recalculateFromEvent` która oblicza statystyki na podstawie kalkulatorów implementujących kontrakt `StatisticCalculatorInterface`. 
Dla przykładu w domain/calculators znajdują się dwa kalkulatory dla instniejących typów statystyk. Dodanie kolejnych wiązać się będzie z dodaniem nowych obiektów kalkulatorów.


### Notyfikacje
Każde zdarzenie powoduje wysłanie do klientów notyfikacji. Przygotowany do tego celu PublisherInterface powinien być zaimplementowany tak aby rozsyłać zdarzenia np na websocket w celu informowania klientów na bierząco o zdarzeniach.

### Transakcyjność
W celu zapewnienia spójności danych należy zapewnić transakcyjność operacji zapisu zdarzeń i wyliczeń statystyk - wtedy zdarzenie zapisze się wraz z zaktualizowaną statystyką.
W przykładzie dodano TransactionalInterface do pełnienia tej roli. 


### Dalsza rozbudowa
- konieczne będzie rozbudowanie testów
- należy zapewnić odpowiednią walidację danych wejściowych i reguł biznesowych

### Uruchomienie testów
`docker exec -it football_events_app vendor/bin/phpunit tests`
`docker exec -it football_events_app vendor/bin/codecept run Api`
