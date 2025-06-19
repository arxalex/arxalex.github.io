import {Component, OnInit} from '@angular/core';
import {AppPreviewComponent} from './app-preview/app-preview.component';
import {AppPreviewModel} from './app-preview/app-preview.model';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-apps-grid',
  imports: [AppPreviewComponent],
  templateUrl: './apps-grid.component.html',
  styleUrl: './apps-grid.component.scss'
})
export class AppsGridComponent implements OnInit {
  appPreviewModels: AppPreviewModel[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<AppPreviewModel[]>('/apps.json')
      .subscribe(data => this.appPreviewModels = data);
  }
}
