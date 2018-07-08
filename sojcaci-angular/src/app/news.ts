export class News {
  id: number;
  description: string;
  created: string;

  constructor(id: number, description: string, created: string) {
    this.id = id;
    this.description = description;
    this.created = created;
  }
}

export class NewsFull extends News {
  visible_until: string;

  constructor(id: number, description: string, created: string, visible_until: string) {
    super(id, description, created);
    this.visible_until = visible_until;
  }

  //  toNews(): News {
  //    return new News(this.description, this.created);
  //  }
}
