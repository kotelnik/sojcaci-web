export class UserInline {
  id: number;
  display_name: string;

  constructor(id: number, display_name: string) {
    this.id = id;
    this.display_name = display_name;
  }

}

export class User extends UserInline {
  first_name: string;
  last_name: string;
  nick_name: string;
  email: string;
  email_notifications_enabled: boolean;
  last_login: string;
  notes: string;
  is_child: boolean;

  constructor(id: number, display_name: string, first_name: string, last_name: string, nick_name: string, email: string, email_notifications_enabled: boolean, notes: string, is_child: boolean) {
  super(id, display_name);
  this.first_name = first_name;
  this.last_name = last_name;
  this.nick_name = nick_name;
  this.email = email;
  this.email_notifications_enabled = email_notifications_enabled;
  this.notes = notes;
  this.is_child = is_child;
  }
}

export class UserFull extends User {
  permissions: string[];
}
