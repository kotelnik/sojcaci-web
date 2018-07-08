import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-welcome',
  templateUrl: './welcome.component.html',
  styleUrls: ['./welcome.component.css']
})
export class WelcomeComponent implements OnInit {

h2 = 'Tohle je úvodní stránka';
data = 'A tady jsou nějaký kecy.';

  constructor() { }

  ngOnInit() {
  }

}
