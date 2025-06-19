import {Component, OnInit} from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-social-links-list',
  templateUrl: './social-links-list.component.html',
  styleUrl: './social-links-list.component.scss'
})
export class SocialLinksListComponent implements OnInit {
  socialLinks: SocialLink[] = [];
  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<SocialLink[]>('/social-links.json')
      .subscribe(data => this.socialLinks = data);
  }
}
