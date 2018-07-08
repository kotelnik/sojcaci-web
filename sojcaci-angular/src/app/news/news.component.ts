import { Component, OnInit } from '@angular/core';

import { DataService } from '../data.service';

import { News } from '../news';

@Component({
  selector: 'app-news',
  templateUrl: './news.component.html',
  styleUrls: ['./news.component.css']
})
export class NewsComponent implements OnInit {

  newss: News[];
  newNews: News = new News(0, "","");

  constructor(private dataService: DataService) { }

  ngOnInit() {
    this.getNews();
  }

  getNews(): void {
  this.dataService.getNewss()
      .subscribe(newss => this.newss = newss);
  }

}
