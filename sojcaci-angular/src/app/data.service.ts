import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { of } from 'rxjs'; // TODO - remove if not needed

import { News, NewsFull } from './news';
import { User } from './user';

import { MessageService } from './message.service';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  constructor(private messageService: MessageService) { }

  getNewss(): Observable<News[]> {
    let newss: News[] = [];
    newss.push(new News(1, "ee", "EEE"));
    newss.push(new News(2, "ff", "FFF"));
    newss.push(new News(3, "gg", "GGG"));
    this.messageService.add("News loaded.", "error");
    return of(newss);
  }

  getNews(id: number): Observable<News> {
    this.messageService.add("news found", "error");
    return of(new News(1, "qq", "QQQ"));
  }

  getUsers(): Observable<User[]> {
    let users: User[] = [];
    users.push(new User(1, "Pechy", "Jiří", "Pech", "Pecháč", "pe@c.h", true, "alergie na mlíko", false));
    users.push(new User(2, "Mikoláš", "Aleš", "Mlynář", "Koště", "koste@seznam.cz", true, "", true));
    this.messageService.add("Users loaded.", "error");
    return of(users);
  }

}
