import { Injectable } from '@angular/core';

import { Message, MessageType } from  './message';

@Injectable({
  providedIn: 'root'
})
export class MessageService {

  messageTypes: { [id: string]: MessageType } = {
      'silent': { type: 'silent', timeout: null },
      'success': { type: 'success', timeout: 3000 },
      'error': { type: 'danger', timeout: 10000 }
  };

  messages: Message[] = [];

  getMessages(): Message[] {
    return this.messages;
  }

  delete(message: Message) {
    const index: number = this.messages.indexOf(message);
    this.messages.splice(index, 1);
  }

  add(messageText: string, messageType: string) { // TODO - use type MessageType
    const message: Message = {
      id: this.messages.length,
      text: messageText,
      type: messageType
    };
    this.messages.push(message);
  }

  clear() {
    this.messages = [];
  }
}
